<?php

namespace App\Repository;

use App\Enums\EstadosEnum;
use App\Enums\EstadosMatriculaEnum;
use App\Enums\TiposCuotaEnum;
use App\Enums\FormasPagoEnum;
use App\Models\Installment;
use Exception;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class InstallmentRepository extends Installment
{
    private $_matriculaRepository;

    /*  Columnas:
            id enrollment_id order type amount status deadline
    */

    public function __construct()
    {
        $this->_matriculaRepository = new EnrollmentRepository();
    }

    public function builderModelRepository()
    {
        return (object) [
            'matricula_id' => null,         // enrollment_id
            'tipo_pago' => null,            //
            'costo_matricula' => null,      //
            'costo_ciclo' => null,          //
            'cuotas' => null,               //

            'detalle_cuotas' => null        // Lista de cuotas para el tipo de pago a credito
            // 'status' => null,            // status
        ];
    }

    public function generarCoutasPago(object $moInstallment)
    //Modificar para que devueva un array de cuotas
    {
        $cuotas = [];
        switch ($moInstallment->tipo_pago) {
            case FormasPagoEnum::CONTADO:
                $cuotas[] = ['enrollment_id' => $moInstallment->matricula_id, 'order' => 1, 'type' => TiposCuotaEnum::MATRICULA, 'amount' => 0.00 , 'deadline' =>  date('Y-m-d')];
                $cuotas[] = ['enrollment_id' => $moInstallment->matricula_id, 'order' => 2, 'type' => TiposCuotaEnum::CICLO, 'amount' => $moInstallment->costo_ciclo , 'deadline' =>  date('Y-m-d')];
                // crear un pago de la matricula a un monto de 0 soles

                break;
            case FormasPagoEnum::CREDITO:
                $cuotas[] = ['enrollment_id' => $moInstallment->matricula_id, 'order' => 1, 'type' => TiposCuotaEnum::MATRICULA, 'amount' => $moInstallment->costo_matricula , 'deadline' =>  date('Y-m-d')];
                if( $moInstallment->detalle_cuotas && count($moInstallment->detalle_cuotas) == $moInstallment->cuotas )
                    for ($i = 0; $i < count($moInstallment->detalle_cuotas); $i++)
                        $cuotas[] = ['enrollment_id' => $moInstallment->matricula_id, 'order' => ($i + 2), 'type' => TiposCuotaEnum::CICLO, 'amount' => $moInstallment->detalle_cuotas[$i]['costo'], 'deadline' => $moInstallment->detalle_cuotas[$i]['fecha']];
                else throw new BadRequestException("Error, no coincide el numero de cuotas con el numero de detalle de cuotas");
                break;
            default:
                throw new BadRequestException("Error, la forma de pago no es valida");
        }
        $matricula = $this->_matriculaRepository::find($moInstallment->matricula_id);
        $matricula->status = EstadosMatriculaEnum::ACTIVO;
        $matricula->payment_type = $moInstallment->tipo_pago;
        $matricula->fees_quantity = $moInstallment->cuotas;
        $matricula->save();
        Installment::insert($cuotas);

        return (count($cuotas))." Cuotas generadas.";
    }

    public function actualizarCoutasPago(int $matricula_id, object $moInstallment)
    {
        $informacionPagos = self::informacionPagosCuotas($matricula_id);
        $matricula = $this->_matriculaRepository::find($matricula_id);
        $matricula->status = EstadosMatriculaEnum::ACTIVO;

        if(!$informacionPagos || !$matricula /* || (round($informacionPagos->monto_deuda_pagado, 2) == ( round((float) $matricula->amount_paid, 2)))  */)
            throw new Exception('Error, la matricula presenta incoherencias en las cuotas de pago');

        $cuotasDesactivadas = self::desactivarCuotasMatricula($matricula_id);
        $cuotasCreadas = self::generarCoutasPago($moInstallment);

        // Creando pagos
        /* if(true){


        } */

        return "$cuotasDesactivadas \n $cuotasCreadas";
    }

    public function evaluarRequisitosActualizacion ( int $matricula_id ){
        $matricula = $this->_matriculaRepository::find($matricula_id);
        if(!$matricula) throw new NotFoundResourceException('Error, no se encontró la matricula');

        $costoCiclo = 0;
        foreach( self::cuotasActivasCiclo($matricula_id) as $cuota )
            $costoCiclo+= $cuota->amount;

        if($costoCiclo != $matricula->period_cost_final){
            $matricula->status = EstadosMatriculaEnum::PENDIENTE_ACTIVACION;
            $matricula->save();
            self::desactivarCuotasMatricula($matricula_id, false);
            return true;
        }
        return false;
    }

    public function desactivarCuotasMatricula(int $matricula_id, bool $autoEliminacion=true){
        $cuotasActivas = self::cuotasActivasTotales($matricula_id);
        $numeroCuotas = count($cuotasActivas);
        foreach ($cuotasActivas as $key=>$cuota) {
            $cuota->status = EstadosEnum::INACTIVO;
            $cuota->observations = "Eliminada automaticamente por Usuario:". Auth::user()->id .' el '. date('Y-m-d h:i:s')."( ".($key + 1)." / $numeroCuotas )";
            $cuota->save();
            if($autoEliminacion) $cuota->delete();
        }
        return "$numeroCuotas Cuotas eliminadas.";
    }

    public function getInformacionCuotasActivas( int $matricula_id ){
        $matricula = $this->_matriculaRepository::find($matricula_id);
        $cuotaMatricula = self::cuotasActivasMatricula($matricula_id);
        $cuotasCiclo =  self::cuotasActivasCiclo($matricula_id);

        if(count($cuotasCiclo)==0 || !$cuotaMatricula || !$matricula) return null;

        $modeloInformacionCuotas = self::builderModelRepository();
        $modeloInformacionCuotas->matricula_id = $matricula->id;
        $modeloInformacionCuotas->costo_matricula = $cuotaMatricula->amount;
        $modeloInformacionCuotas->costo_ciclo = $matricula->period_cost_final;
        $modeloInformacionCuotas->cuotas = $matricula->fees_quantity;

        switch ($matricula->payment_type) {
            case FormasPagoEnum::CONTADO:
                $modeloInformacionCuotas->tipo_pago = FormasPagoEnum::CONTADO;
                if( count( $cuotasCiclo ) !=1 ) throw new Exception('Ocurrio un error, numero de cuotas para el ciclo erroneo '. (count( $cuotasCiclo ) !=1) );
                $modeloInformacionCuotas->detalle_cuotas = null;
                break;
            case FormasPagoEnum::CREDITO:
                $modeloInformacionCuotas->tipo_pago = FormasPagoEnum::CREDITO;
                if( count( $cuotasCiclo ) != $modeloInformacionCuotas->cuotas ) throw new Exception('Ocurrio un error, no coinciden el numero de cuotas para el ciclo');

                $cuotas = array();
                foreach($cuotasCiclo as $cuotaIterador) $cuotas[] = [ "costo"=>$cuotaIterador->amount, "fecha"=>$cuotaIterador->deadline ];
                $modeloInformacionCuotas->detalle_cuotas = $cuotas;
                break;
            default: throw new Exception('Error, forma de pago en la matricula no identificada ['. $matricula->payment_type . ']');
        }
        return $modeloInformacionCuotas;
    }

    public function informacionPagosCuotasConNotas($matricula_id) // Antes getInformacionPagosYCuotas
    {
        $cuotas = self::cuotasActivasTotales($matricula_id);
        if (count($cuotas) == 0) return null;

        $cuotas_matricula = array();
        $cuotas_ciclo = array();
        $monto_deuda_inicial = 0;   // Costo de la deuda inicial;
        $monto_pagado = 0;          // Monto total abonado;

        foreach ($cuotas as $cuotaIterador) {
            $informacionCuota =  self::buildInformacionCuotas($cuotaIterador, true);

            $monto_deuda_inicial += $cuotaIterador->amount;
            $monto_pagado += $informacionCuota->monto_pagado;

            switch ($cuotaIterador->type) {
                case TiposCuotaEnum::CICLO: $cuotas_ciclo[$cuotaIterador->id] = $informacionCuota; break;
                case TiposCuotaEnum::MATRICULA: $cuotas_matricula[$cuotaIterador->id] = $informacionCuota; break;
                default: throw new NotFoundResourceException('Error, tipo de cuota no identificado ['. $cuotaIterador->type .']');
            }
        }
        $informacionPagos= [
            'matricula' => $cuotas_matricula,
            'ciclo' => $cuotas_ciclo,
            'monto_deuda_inicial' => $monto_deuda_inicial,
            'monto_deuda_pagado' => $monto_pagado,
            'monto_deuda_pendiente' => $monto_deuda_inicial - $monto_pagado,
            'total_pagado' => ($monto_deuda_inicial - $monto_pagado) == 0,
        ];
        /* dd($informacionPagos); */
        return $informacionPagos ;
    }

    public function informacionPagosCuotas($matricula_id)   //Antes getCuotasCiclo
    {
        $cuotas = self::cuotasActivasCiclo($matricula_id);
        if (count($cuotas) == 0) return null;

        $cuotas_ciclo = array();
        $monto_deuda_inicial = 0;   // Costo de la deuda inicial;
        $monto_pagado = 0;          // Costo de la deuda inicial;

        foreach ($cuotas as $cuotaIterador) {
            $informacionCuota = self::buildInformacionCuotas($cuotaIterador);
            $monto_deuda_inicial += $cuotaIterador->amount;
            $monto_pagado += $informacionCuota->monto_pagado;
            $cuotas_ciclo [] = $informacionCuota;
        }

        return (object) [
            'cuotas_ciclo' => $cuotas_ciclo,
            'monto_deuda_inicial' => $monto_deuda_inicial,
            'monto_deuda_pagado' => $monto_pagado,
            'monto_deuda_pendiente' => $monto_deuda_inicial - $monto_pagado,
            'total_pagado' => ($monto_deuda_inicial - $monto_pagado) == 0,
        ];
    }

    private function cuotasActivasTotales(int $matricula_id){
        return  Installment::where('enrollment_id', $matricula_id)
                            ->where('status', EstadosEnum::ACTIVO)
                            ->where('deleted_at', null)
                            ->orderBy('order', 'asc')
                            ->get();
    }

    private function cuotasActivasMatricula(int $matricula_id){
        $cuotasMatricula =  Installment::where('enrollment_id', $matricula_id)
                                        ->where('type',  TiposCuotaEnum::MATRICULA)
                                        ->where('status', EstadosEnum::ACTIVO)
                                        ->where('deleted_at', null)
                                        ->orderBy('order', 'asc')
                                        ->get();
        $numeroCuotas = count($cuotasMatricula);
        if($numeroCuotas >1 ) throw new Exception("Error, el numero de cuotas para la matricula no es valido,  [$numeroCuotas cuotas encontradas] ");
        return $numeroCuotas == 1 ? $cuotasMatricula[0] : null;
    }

    private function cuotasActivasCiclo(int $matricula_id){
        return  Installment::where('enrollment_id', $matricula_id)
                            ->where('type',  TiposCuotaEnum::CICLO)
                            ->where('status', EstadosEnum::ACTIVO)
                            ->where('deleted_at', null)
                            ->orderBy('order', 'asc')
                            ->get();
    }

    private function buildInformacionCuotas( Installment $cuota, bool $conNotasCredito =false ){
        $informacionCuota = (object)[
                                        'id' => $cuota->id,
                                        'orden' => $cuota->order,
                                        'tipo' => TiposCuotaEnum::getName($cuota->type),
                                        'monto_cuota' => $cuota->amount,
                                        'monto_pagado' => 0,
                                        'pagos' => array(),
                                        'total_pagado' => $cuota->amount == 0,  // verdadero si no se tiene deuda
                                        'fecha_limite' => $cuota->deadline ,
                                        'fecha_matricula' => $cuota->created_at,
                                    ];
        $pagosRealizados = $cuota->payments;
        if (count($pagosRealizados)>0) {
            $pagos = array();
            $montoAbonado =0;
            foreach ($pagosRealizados as $pago) {
                $pagosTemporal = (object) [
                                            'id' => $pago->id,
                                            'cuota_id' => $pago->installment_id,
                                            'monto' => $pago->amount,
                                            'tipo' => $pago->type,
                                            'concepto' => $pago->concept_type,
                                            'usuario' => $pago->user->nombreCompleto(),
                                            'serie' => $pago->serie,
                                            'numeracion' => $pago->numeration,
                                            'fecha_pago' => date ( 'Y-m-d h:i:s A', strtotime($pago->created_at)),
                                            'es_devolucion' => null,
                                            'fecha_devolucion' => null,
                                        ];
                $notaPago = $pago->note;
                if(! $notaPago ){
                    $pagosTemporal->es_devolucion=false;
                    $pagosTemporal->fecha_devolucion=null;
                    $pagos[$pago->id] = $pagosTemporal;
                    $montoAbonado+=$pago->amount;
                }
                else
                    if($conNotasCredito){
                        $pagosTemporal->es_devolucion=true;
                        $pagosTemporal->fecha_devolucion= date ( 'Y-m-d h:i:s A', strtotime($notaPago->created_at));
                        $pagos[$pago->id] = $pagosTemporal;
                    }
            }
            $informacionCuota->monto_pagado = $montoAbonado;
            $informacionCuota->pagos = $pagos;
            $informacionCuota->total_pagado = round((float) $cuota->amount, 2) <= round((float) $montoAbonado, 2);
        }
        return $informacionCuota;
    }



    /* public function getHistorialPagos(int $matricula_id)
        {
            $cuotas = Installment::join('payments', 'installments.id', 'payments.installment_id')
                ->join('')
                ->where('payments.enrollment_id', $matricula_id)
                ->where('installments.status', EstadosEnum::ACTIVO)
                ->where('installments.deleted_at', null)
                ->where('payments.deleted_at', null)
                ->select(
                    'installments.id as cuota_id',
                    'installments.order as orden',
                    'installments.type as tipo',
                    'installments.amount as monto_cuota',
                    'payments.id as pago_id',
                    'payments.amount',
                    'payments.concept_type',
                    'payments.serie',
                    'payments.user_id',
                    'payments.numeration',
                );
            if (!$cuotas) return null;

            $historial = array();
            foreach ($cuotas as $cuota) {
                $cuotaTemp = (object)[];
            }
        }
    */

    // para la generacion de la boleta
    /* public function getInformacionPago(int $cuota_id)
    {
    } */
}
