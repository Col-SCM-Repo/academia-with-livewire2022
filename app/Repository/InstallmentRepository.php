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
                $cuotas[] = ['enrollment_id' => $modelInstallment->matricula_id, 'order' => 0, 'type' => TiposCuotaEnum::MATRICULA, 'amount' => 0.00];
                $cuotas[] = ['enrollment_id' => $modelInstallment->matricula_id, 'order' => 1, 'type' => TiposCuotaEnum::CICLO, 'amount' => $modelInstallment->costo_ciclo];
                break;
            case FormasPagoEnum::CREDITO:
            case strtoupper(FormasPagoEnum::CREDITO):
                $cuotas[] = ['enrollment_id' => $modelInstallment->matricula_id, 'order' => 0, 'type' => TiposCuotaEnum::MATRICULA, 'amount' => $modelInstallment->costo_matricula];
                $costo_cuota = round($modelInstallment->costo_ciclo / $modelInstallment->cuotas, 2);
                for ($i = 0; $i < $modelInstallment->cuotas; $i++) {
                    $cuotas[] = ['enrollment_id' => $modelInstallment->id, 'order' => ($i + 1), 'type' => TiposCuotaEnum::CICLO, 'amount' => $costo_cuota];
                }
                break;
            default:
                return null;
        }
        Installment::insert($cuotas);
        return true;
    }

    public function actualizarCoutasPago($matricula_id)
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
    }


    public function historialMatriculas(int $aula_id)
    {
    }

    public function buscarMatricula(string $dni_estudiante)
    {
    }

    public function informacionDeMatricula(int $estudiante_id, string $dni_estudiante)
    {
    }
}
