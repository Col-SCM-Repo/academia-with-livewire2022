<?php

namespace App\Repository;

use App\Enums\TiposConceptoPagoEnun;
use App\Enums\TiposPagoFacturaEnum;
use App\Models\Payment;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class PaymentRepository extends Payment
{
    /*
        COLUMNAS:
                    id	installment_id	amount	type	concept_type
                    user_id	payment_id	serie	numeration
    */

    /*PENDIENTE */
    // llamara a un metodo de pagosRepository para generar un codigo de pago para cada pago realizado en la cuota
    // Crear un metodo en payments para generar notas y colocar un parametro para descontar el amountpayment automatico de la matricula

    private $_cuotasRepository;

    public function __construct()
    {
        $this->_cuotasRepository = new InstallmentRepository();
    }

    public function builderModelRepository()
    {
        return (object) [
            //'id' => null,
            'cuota_id' => null,                 // installment_id
            'matricula_id' => null,             //
            'montoPagado' => null,              // amount

            'pago_id' => null                   // payment_id

            // 'serie' => null,                 // ...evaluar
            // 'numeration' => null,            // ...evaluar
        ];
    }

    public function pagarMatricula( object $modelBuilder ){     // Solo requiere de cuota_id y montoPagado
        $cuota = $this->_cuotasRepository::find($modelBuilder->cuota_id);
        if (!$cuota) throw new NotFoundResourceException('No se encontro la cuota especificada');
        if($cuota->abonado() >= $cuota->amount) throw new NotFoundResourceException('La cuota ya esta pagada');

        if(round($modelBuilder->montoPagado, 2) == round($cuota->amount, 2))
            return [self::almacenarPago($cuota->id, $modelBuilder->montoPagado)];

        throw new Exception('Error, el pago de la matricula no se puede fraccionar');
    }

    public function pagarCiclo( object $modelBuilder ){     // Solo requiere de matricula_id y montoPagado
        $informacionDeuda = $this->_cuotasRepository->informacionPagosCuotas($modelBuilder->matricula_id);
        if(!$informacionDeuda) throw new NotFoundResourceException('Error, no se encontro informacion sobre la deuda');

        if( $informacionDeuda->monto_deuda_pendiente >= $modelBuilder->montoPagado  ){
            $pagos = array();
            $saldo=round($modelBuilder->montoPagado,2);
            foreach ( $informacionDeuda->cuotas_ciclo  as $cuotaIterador ) {
                if( $saldo < 0){
                    Log::debug("[PaymentRepository::pagarCiclo] [CRITICO] El saldo [$saldo] es negativo  ".$cuotaIterador->id.' fecha '.date('Y-m-d H:i:s'));
                    throw new Exception('Ocurrio un error critico al registrar pagos.'.$cuotaIterador->id);
                };
                if($saldo==0) break;
                if(!$cuotaIterador->total_pagado){
                    if( ! $cuotaIterador->pagos ){
                        if( $saldo>= $cuotaIterador->monto_cuota ){
                            $pagos[] = self::almacenarPago($cuotaIterador->id, $cuotaIterador->monto_cuota );
                            $saldo -= $cuotaIterador->monto_cuota;
                        }
                        else{
                            $pagos[] = self::almacenarPago($cuotaIterador->id, $saldo, false);
                            $saldo = 0;
                        }
                    }
                    else{
                        $montoPendienteCuota = round($cuotaIterador->monto_cuota,2) - round($cuotaIterador->monto_pagado, 2);
                        if( $saldo>= $montoPendienteCuota ){
                            $pagos[] = self::almacenarPago($cuotaIterador->id, $montoPendienteCuota, false);
                            $saldo -= $montoPendienteCuota;
                        }
                        else{
                            $pagos[] = self::almacenarPago($cuotaIterador->id, $saldo, false);
                            $saldo = 0;
                        }
                    }
                }
            }
            return $pagos;
        }
        else
            throw new Exception('Error, el monto abonado no puede ser mayor a la deuda pendiente de S./'.$informacionDeuda->monto_deuda_pendiente.'');
    }

    public function anularPago( int $pago_id ){
        $pago = Payment::find($pago_id);
        // dd($pago, $pago_id);
        if($pago){
            return self::almacenarPago(null, $pago->amount, false, $pago_id );
        }
        else
            throw new NotFoundResourceException('Error, no se encuentra el pago a eliminar id:'.$pago_id);

    }

    private function almacenarPago( $cuota_id, $monto, bool $entero=true, $pago_id = null)
    {
        $pago = new Payment();
        $pago->installment_id = $cuota_id;
        $pago->amount = $monto;
        $pago->type = $pago_id ? TiposPagoFacturaEnum::DEVOLUCION : TiposPagoFacturaEnum::TICKET;
        $pago->concept_type = $entero? TiposConceptoPagoEnun::ENTERO : TiposConceptoPagoEnun::PARCIAL;
        $pago->user_id = Auth::user()->id;
        $pago->payment_id = $pago_id;
        $pago->save();

        // $pago->serie = null;
        $pago->numeration = str_pad($pago->id, 6, "0", STR_PAD_LEFT);
        $pago->save();
        return $pago;
    }






/*
    public function registrarPago(object $modelBuilder)
    {
        $cuota = $this->_cuotasRepository::find($modelBuilder->cuota_id);
        if (!$cuota) throw new NotFoundResourceException('No se encontro la cuota especificada');

        $pago = null;
        if ($modelBuilder->autoFraccionamiento) {                   // Pago para el ciclo (se puede fraccionar)
            if ($cuota->amount == $modelBuilder->montoPagado)       // (sin fraccionar)
                $pago = self::crearPago($modelBuilder->cuota_id, $modelBuilder->montoPagado);
            else {  // (Fraccionado)
                $cuotasDeCiclo = (object) $this->_cuotasRepository->informacionPagosCuotas($cuota->enrollment_id);

                if ($modelBuilder->montoPagado > $cuotasDeCiclo->monto_deuda_pendiente)
                    throw new BadRequestException('Error, el monto pagado no puede ser mayor a la deuda');

                dd($cuotasDeCiclo);

                $montoDiferencia = 0;
                foreach ($cuotasDeCiclo->ciclo as $cuotaIterador) {
                    if (!$cuotaIterador->esta_pagado) {
                        if ($cuotaIterador->id == $cuota->id) {
                            $pagoRegistrado = self::crearPago($cuota->id, $modelBuilder->montoPagado, true);
                            if ($pagoRegistrado)
                                $montoDiferencia = $cuota->amount - $modelBuilder->montoPagado;
                        }
                        if ($montoDiferencia != 0) {
                            $cuotaActualizada = self::actualizarMontoCuota($cuotaIterador->id,  $montoDiferencia);
                            $montoDiferencia = $cuotaActualizada->vuelto;
                        }
                    }
                }

                // ...........
            }
        } else {    // Pago para la matricula (no fraccionado)
            if (round($modelBuilder->montoPagado, 2) == round($cuota->amount, 2))
                $pago = self::crearPago($modelBuilder->cuota_id, $modelBuilder->montoPagado);
            else {
                Log::debug('error de fraccionamiento (no coincide el pago con la cuota de matricula) [Payment repository::registrarPago]');
                return null;
            }
        }
        return $pago;
    }


    private function crearPago($cuota_id,  $montoPagado, $actualizarCuota = false)
    {
        if ($actualizarCuota) {
            $cuota = $this->_cuotasRepository::find($cuota_id);
            if ($cuota) {
                $cuota->amount = $montoPagado;
                $cuota->save();
                return self::crearPago($cuota_id, $montoPagado);
            }
            return null;
        } else {
            $pago = new Payment();
            $pago->installment_id = $cuota_id;
            $pago->amount = $montoPagado;
            $pago->type = TiposPagoFacturaEnum::TICKET;
            $pago->concept_type =  TiposConceptoPagoEnun::ENTERO;
            $pago->user_id = Auth::user()->id;
            $pago->save();
            return $pago;
        }
    }

    private function actualizarMontoCuota($cuota_id, $monto_adicionado)
    // El monto adicionado puede restarle o sumarle a la cuota (+ suma, - resta)
    {
        if ($monto_adicionado == 0) return null;

        $cuota = $this->_cuotasRepository::find($cuota_id);
        if ($cuota) {
            $vuelto = 0;
            $montoActualizado = null;

            if ($monto_adicionado == $cuota->amount) {
                $montoActualizado = 0;
                $vuelto = 0;
            }
            if ($monto_adicionado < $cuota->amount) {
                $montoActualizado = $cuota->amount - $monto_adicionado;
                $vuelto = 0;
            }
            if ($monto_adicionado > $cuota->amount) {
                $montoActualizado = 0;
                $vuelto = $monto_adicionado - $cuota->amount;
            }

            $cuota->amount = $montoActualizado;
            $cuota->save();
            $cuota->vuelto = $vuelto;
            return $cuota;
        }
        return false;
    }

    public function to_pay_installment($objPago)
    {
    } */
}
