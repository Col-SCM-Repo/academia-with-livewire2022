<?php

namespace App\Repository;

use App\Enums\EstadosEnum;
use App\Enums\TiposCuotaEnum;
use App\Enums\FormasPagoEnum;
use App\Models\Installment;
use Illuminate\Support\Facades\Log;

class InstallmentRepository extends Installment
{
    protected $paymentRepository;

    /*
        Columnas: 
                    id, enrollment_id, order, type, amount, status

        Nota:
                    Al crear deben haber minimo dos categorias de pagos (matricula, cuota)
    */

    public function __construct()
    {
    }

    public function builderModelRepository()
    {
        return $modelRepository = (object) [
            'matricula_id' => null,
            'tipo_pago' => null,
            'costo_matricula' => null,
            'costo_ciclo' => null,
            'cuotas' => null,

            'status' => null,
        ];
    }

    public function generarCoutasPago(object $modelInstallment)
    {
        $cuotas = [];
        Log::debug((array) $modelInstallment);
        switch ($modelInstallment->tipo_pago) {
            case FormasPagoEnum::CONTADO:
            case strtoupper(FormasPagoEnum::CONTADO):
                $cuotas[] = ['enrollment_id' => $modelInstallment->matricula_id, 'order' => 1, 'status' => EstadosEnum::ACTIVO, 'type' => TiposCuotaEnum::MATRICULA, 'amount' => 0.00];
                $cuotas[] = ['enrollment_id' => $modelInstallment->matricula_id, 'order' => 2, 'status' => EstadosEnum::ACTIVO, 'type' => TiposCuotaEnum::CICLO, 'amount' => $modelInstallment->costo_ciclo];
                break;
            case FormasPagoEnum::CREDITO:
            case strtoupper(FormasPagoEnum::CREDITO):
                $cuotas[] = ['enrollment_id' => $modelInstallment->matricula_id, 'order' => 1, 'status' => EstadosEnum::ACTIVO, 'type' => TiposCuotaEnum::MATRICULA, 'amount' => $modelInstallment->costo_matricula];
                $costo_cuota = round($modelInstallment->costo_ciclo / $modelInstallment->cuotas, 2);
                for ($i = 0; $i < $modelInstallment->cuotas; $i++) {
                    $cuotas[] = ['enrollment_id' => $modelInstallment->matricula_id, 'order' => ($i + 2), 'status' => EstadosEnum::ACTIVO, 'type' => TiposCuotaEnum::CICLO, 'amount' => $costo_cuota];
                }
                break;
            default:
                return null;
        }
        Installment::insert($cuotas);
        return true;
    }

    /* public function actualizarCoutasPago($matricula_id)
    {
        $cuotas = self::where('enrollment_id', $matricula_id)
            ->where('status', EstadosEnum::ACTIVO)
            ->get();

        if (count($cuotas) == 0) return null;

        $montoDevuelto = 0;

        foreach ($cuotas as $cuota) {
            $cuota->status = EstadosEnum::INACTIVO;
            $cuota->save();
        }
    } */

    public function getInformacionPagosYCuotas($matricula_id)
    {
        $cuotas = Installment::where('enrollment_id', $matricula_id)
            ->where('status', EstadosEnum::ACTIVO)
            ->where('deleted_at', null)
            ->get();
        if (count($cuotas) == 0) return null;

        $cuotas_matricula = array();
        $cuotas_ciclo = array();
        $totalPagado = true;
        $monto_deuda_inicial = 0;   // Costo de la deuda inicial;
        $monto_pagado = 0;          // Costo de la deuda inicial;


        foreach ($cuotas as $cuotaIterador) {
            $cuotaTemp = (object)[
                'id' => $cuotaIterador->id,
                'orden' => $cuotaIterador->order,
                //'matricula_id' => $cuotaIterador->id,
                'tipo' => '',
                'monto_cuota' => $cuotaIterador->amount,
                'pago_id' => null,
                'monto_pagado' => null,
                'fecha_pago' => null,
                'usuario_registro_pago' => null,
                'total_pagado' => $cuotaIterador->amount == 0,
                'fecha_matricula' => $cuotaIterador->created_at,
            ];
            $monto_deuda_inicial += $cuotaIterador->amount;

            $pago = $cuotaIterador->payment;
            if ($pago) {
                $cuotaTemp->pago_id = $pago->id;
                $cuotaTemp->monto_pagado = $pago->amount;
                $cuotaTemp->usuario_registro_pago = $pago->user->nombreCompleto();
                $cuotaTemp->fecha_pago = $pago->created_at;
                $cuotaTemp->total_pagado = round((float) $pago->amount, 2) >= round((float) $cuotaTemp->monto_cuota, 2);

                $monto_pagado += $cuotaTemp->monto_pagado;
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
                'pago_id' => null,
                'monto_pagado' => null,
                'esta_pagado' => false,
            ];
            $monto_deuda_inicial += $cuotaIterador->amount;

            $pago = $cuotaIterador->payment;
            if ($pago) {
                $cuotaTemp->pago_id = $pago->id;
                $cuotaTemp->monto_pagado = $pago->amount;
                $cuotaTemp->esta_pagado = round((float) $pago->amount, 2) >= round((float) $cuotaTemp->monto_cuota, 2);
                $monto_pagado += $cuotaTemp->monto_pagado;
            }
            $cuotas_ciclo[$cuotaIterador->id] = $cuotaTemp;
        }
        return [
            'ciclo' => $cuotas_ciclo,
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
    public function getInformacionPago(int $cuota_id)
    {
    }
}
