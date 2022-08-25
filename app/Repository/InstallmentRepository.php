<?php

namespace App\Repository;

use App\Enums\EstadosEnum;
use App\Enums\TiposCuotaEnum;
use App\Enums\FormasPagoEnum;
use App\Models\Installment;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class InstallmentRepository extends Installment
{
    //protected $_enrollmentRepository;

    /*  Columnas:
            id enrollment_id order type amount status deadline
    */

    public function __construct()
    {
        //$this->_enrollmentRepository = new EnrollmentRepository();
    }

    public function builderModelRepository()
    {
        return (object) [
            'matricula_id' => null,         // enrollment_id
            'tipo_pago' => null,            //
            'costo_matricula' => null,      //
            'costo_ciclo' => null,          //
            'cuotas' => null,               //

            // 'status' => null,            // status
        ];
    }

    public function generarCoutasPago(object $moInstallment)
    {
        $cuotas = [];
        switch ($moInstallment->tipo_pago) {
            case FormasPagoEnum::CONTADO:
            case strtoupper(FormasPagoEnum::CONTADO):
                $cuotas[] = ['enrollment_id' => $moInstallment->matricula_id, 'order' => 1, 'type' => TiposCuotaEnum::MATRICULA, 'amount' => 0.00 , 'deadline' =>  date('Y-m-d')];
                $cuotas[] = ['enrollment_id' => $moInstallment->matricula_id, 'order' => 2, 'type' => TiposCuotaEnum::CICLO, 'amount' => $moInstallment->costo_ciclo , 'deadline' =>  date('Y-m-d')];
                // crear un pago de la matricula a un monto de 0 soles
                break;
            case FormasPagoEnum::CREDITO:
            case strtoupper(FormasPagoEnum::CREDITO):
                $cuotas[] = ['enrollment_id' => $moInstallment->matricula_id, 'order' => 1, 'type' => TiposCuotaEnum::MATRICULA, 'amount' => $moInstallment->costo_matricula , 'deadline' =>  date('Y-m-d')];
                for ($i = 0; $i < count($moInstallment->detalle_cuotas); $i++)
                    $cuotas[] = ['enrollment_id' => $moInstallment->matricula_id, 'order' => ($i + 2), 'type' => TiposCuotaEnum::CICLO, 'amount' => $moInstallment->detalle_cuotas[$i]['costo'], 'deadline' => $moInstallment->detalle_cuotas[$i]['fecha']];
                break;
            default:
                throw new BadRequestException("Error, la forma de pago no es valida");
        }
        return Installment::insert($cuotas);
    }

    public function actualizarCoutasPago(int $matricula_id)
    {
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
                        'es_devolucion' => null,
                        'fecha_devolucion' => null,
                    ];
                    if($pago->payment_id == null ){
                        $pagoTemp->es_devolucion=false;
                        $montoAbonado+=$pago->amount;
                    }
                    else{
                        $pagoTemp->es_devolucion=true;
                        $pagoTemp->fecha_devolucion=null;       // pendiente de agregar fecha .... en funcion a la nota de credito
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
                    if($pago->payment_id == null ){
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
