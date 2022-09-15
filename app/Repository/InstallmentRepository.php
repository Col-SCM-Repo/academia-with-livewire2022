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

        return (count($cuotas))." Cuotas generadas";
    }

    public function actualizarCoutasPago(int $matricula_id, object $moInstallment)
    {
        // Calcular monto abonado

        // validar monto abonado y monto de matricula

        // crear notas de pagos

        // generar nuevas cuotas

    }
    public function desactivarCuotasyCrearNotasPago(int $matricula_id){
        $cuotasActivas = Installment::where('enrollment_id', $matricula_id)->where('status', EstadosEnum::ACTIVO)->where('deleted_at', null)->get();
        $numeroCuotas = count($cuotasActivas);

        foreach ($cuotasActivas as $key=>$cuota) {
            /*INCONCLUSOOOOOOOOOOOOOO */
            // llamara a un metodo de pagosRepository para generar un codigo de pago para cada pago realizado en la cuota
            // Crear un metodo en payments para generar notas y colocar un parametro para descontar el amountpayment automatico de la matricula


            $cuota->status = EstadosEnum::INACTIVO;
            $cuota->observations = "Eliminada automaticamente por Usuario:". Auth::user()->id .' el '. date('Y-m-d h:i:s')."( $key / $numeroCuotas )";
            $cuota->save();
        }

    }


    public function getInformacionCuotasActivas( int $matricula_id ){
        $cuotasCiclo =  Installment::where('enrollment_id', $matricula_id) ->where('type', TiposCuotaEnum::CICLO)
                                    ->where('status', EstadosEnum::ACTIVO) ->where('deleted_at', null)
                                    ->orderBy('order', 'asc')->get();

        $cuotaMatricula = Installment::where('enrollment_id', $matricula_id)->where('type', TiposCuotaEnum::MATRICULA)
                                    ->where('status', EstadosEnum::ACTIVO)  ->where('deleted_at', null)
                                    ->first();

        $matricula = $this->_matriculaRepository::find($matricula_id);

        if(count($cuotasCiclo)==0 || ! $cuotaMatricula | !$matricula) return null;

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

    public function getInformacionPagosYCuotas($matricula_id)
    {
        $cuotas = Installment::where('enrollment_id', $matricula_id)->where('status', EstadosEnum::ACTIVO)
                ->where('deleted_at', null)->get();
        if (count($cuotas) == 0) return null;

        $cuotas_matricula = array();
        $cuotas_ciclo = array();
        $monto_deuda_inicial = 0;   // Costo de la deuda inicial;
        $monto_pagado = 0;          // Monto total abonado;

        foreach ($cuotas as $cuotaIterador) {
            $cuotaTemp = (object)[
                'id' => $cuotaIterador->id,
                'orden' => $cuotaIterador->order,
                'tipo' => null,
                'monto_cuota' => $cuotaIterador->amount,
                'pagos' => null,
                'monto_pagado' => 0,
                'total_pagado' => $cuotaIterador->amount == 0,
                'fecha_limite' => $cuotaIterador->deadline ,
                'fecha_matricula' => $cuotaIterador->created_at,
            ];
            $monto_deuda_inicial += $cuotaIterador->amount;

            $pagosAbonados = $cuotaIterador->payments;
            if (count($pagosAbonados)>0) {
                $pagos = array();
                $montoAbonado =0;
                foreach ($pagosAbonados as $pago) {
                    $pagoTemp = (object) [
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
                    if(!$notaPago){
                        $pagoTemp->es_devolucion=false;
                        $pagoTemp->fecha_devolucion=null;
                        $montoAbonado+=$pago->amount;
                    }
                    else{
                        $pagoTemp->es_devolucion=true;
                        $pagoTemp->fecha_devolucion= date ( 'Y-m-d h:i:s A', strtotime($notaPago->created_at));       // pendiente de agregar fecha .... en funcion a la nota de credito
                    }
                    $pagos[] = $pagoTemp;
                }
                $cuotaTemp->monto_pagado = $montoAbonado ;
                $cuotaTemp->total_pagado = round((float) $cuotaIterador->amount, 2) <= round((float) $montoAbonado, 2);
                $cuotaTemp->pagos = $pagos;
                $monto_pagado += $montoAbonado;
            }

            if ($cuotaIterador->type == TiposCuotaEnum::CICLO) {
                $cuotaTemp->tipo = 'CICLO';
                $cuotas_ciclo[$cuotaIterador->id] = $cuotaTemp;
            } else {
                $cuotaTemp->tipo = 'MATRICULA';
                $cuotas_matricula[$cuotaIterador->id] = $cuotaTemp;
            }
        }
        return [
            'matricula' => $cuotas_matricula,
            'ciclo' => $cuotas_ciclo,
            'monto_deuda_inicial' => $monto_deuda_inicial,
            'monto_deuda_pagado' => $monto_pagado,
            'monto_deuda_pendiente' => $monto_deuda_inicial - $monto_pagado,
            'total_pagado' => ($monto_deuda_inicial - $monto_pagado) == 0,
        ];
    }

    public function getCuotasCiclo($matricula_id)
    {
        $cuotas = Installment::where('enrollment_id', $matricula_id)
            ->where('type',  TiposCuotaEnum::CICLO)
            ->where('status', EstadosEnum::ACTIVO)
            ->where('deleted_at', null)
            ->orderBy('order')
            ->get();
        if (count($cuotas) == 0) return null;

        $cuotas_ciclo = array();
        $monto_deuda_inicial = 0;   // Costo de la deuda inicial;
        $monto_pagado = 0;          // Costo de la deuda inicial;

        foreach ($cuotas as $cuotaIterador) {
            $cuotaTemp = (object)[
                'id' => $cuotaIterador->id,
                'orden' => $cuotaIterador->order,
                'monto_cuota' => $cuotaIterador->amount,
                'monto_pagado' => null,
                'pagos' => null,
                'total_pagado' => $cuotaIterador->amount == 0,
                'fecha_limite' => $cuotaIterador->deadline ,
            ];

            $monto_deuda_inicial += $cuotaIterador->amount;

            $pagosAbonados = $cuotaIterador->payments;
            if (count($pagosAbonados)>0) {
                $pagos = array();
                $montoAbonado =0;
                foreach ($pagosAbonados as $pago) {
                    $notaPago = $pago->note;
                    if(!$notaPago ){
                        $pagos[$pago->id] = (object) [
                            'id' => $pago->id,
                            'cuota_id' => $pago->installment_id,
                            'monto' => $pago->amount,
                            'tipo' => $pago->type,
                            'concepto' => $pago->concept_type,
                            'usuario' => $pago->user->nombreCompleto(),
                            'serie' => $pago->serie,
                            'numeracion' => $pago->numeration,
                        ];
                        $montoAbonado+=$pago->amount;
                    }
                }
                $cuotaTemp->monto_pagado = $montoAbonado ;
                $cuotaTemp->total_pagado = round((float) $cuotaIterador->amount, 2) <= round((float) $montoAbonado, 2);
                $cuotaTemp->pagos = $pagos;
                $monto_pagado += $montoAbonado;
            }
            $cuotas_ciclo [] = $cuotaTemp;
        }

        return [
            'cuotas_ciclo' => $cuotas_ciclo,
            'monto_deuda_inicial' => $monto_deuda_inicial,
            'monto_deuda_pagado' => $monto_pagado,
            'monto_deuda_pendiente' => $monto_deuda_inicial - $monto_pagado,
            'total_pagado' => ($monto_deuda_inicial - $monto_pagado) == 0,
        ];
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
