<?php

namespace App\Repository;

use App\Enums\EstadosEnum;
use App\Enums\TiposConceptoPagoEnun;
use App\Enums\TiposPagoFacturaEnum;
use App\Models\Payment;
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

    private $_cuotasRepository;

    public function __construct()
    {
        $this->_cuotasRepository = new InstallmentRepository();
    }

    public function builderModelRepository()
    {
        return (object) [
            'id' => null,
            'cuota_id' => null,
            'montoPagado' => null,        // Monto pagado
            'autoFraccionamiento' => true
            // 'payment_id' => null,
            // 'serie' => null,
            // 'numeration' => null,    // numeracion ya no se va a utilizar 
        ];
    }

    public function registrarPago(object $modelBuilder)
    {
        $cuota = $this->_cuotasRepository::find($modelBuilder->cuota_id);
        if (!$cuota) throw new NotFoundResourceException('No se encontro la cuota especificada');

        $pago = null;
        if ($modelBuilder->autoFraccionamiento) {                   // Pago para el ciclo (se puede fraccionar)
            if ($cuota->amount == $modelBuilder->montoPagado)       // (sin fraccionar)
                $pago = self::crearPago($modelBuilder->cuota_id, $modelBuilder->montoPagado);
            else {  // (Fraccionado)
                $cuotasDeCiclo = (object) $this->_cuotasRepository->getCuotasCiclo($cuota->enrollment_id);

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
    }
}
