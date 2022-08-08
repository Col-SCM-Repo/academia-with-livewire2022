<?php

namespace App\Repository;

use App\Models\Level;
use App\Models\Period;
use App\Models\Type_code;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class LevelRepository extends Level
{
    public function builderModelRepository()
    {
        return (object) [
            'id' => null,           // 
            'tipo_nivel' => null,   // type_id
            'periodo_id' => null,   // period_id
            'fInicio' => null, // start_date
            'fFin' => null,    // end_date
            'costo' => null,       // price
            'estado' => null,       // status  - 1 activo  /  0 inactivo
        ];
    }

    public function getNivelPorId(int $nivel_id)
    {
        return Level::find($nivel_id);
    }

    public function buscarNiveles(int $periodo_id, int $estado = -5)
    {
        return Level::join('type_codes', 'levels.type_id', '=', 'type_codes.id')
            ->where('period_id', $periodo_id)
            ->where(function ($query) use ($estado) {
                if ($estado != -5) {
                    $query->where('levels.status', '=', $estado);
                }
            },)
            ->select([
                'levels.id',
                'levels.period_id',
                'type_codes.description',
                'type_codes.type',
                'levels.start_date',
                'levels.end_date',
                'levels.price',
                'levels.status',
            ])->get();
    }

    public function buscarAulasPorNivel(int $periodo_id)
    {
        $periodoActual = Period::find($periodo_id);
        if (!$periodoActual) throw new NotFoundResourceException("Error, no se encontró el periodo indicado");

        $dataNiveles  = array();
        foreach ($periodoActual->levels as $nivel) {
            if ($nivel->status != 1) continue;
            $nivelTemp = [
                'periodo_id' => $periodoActual->id,
                'nivel_id' => $nivel->id,
                'nivel_nombre' => $nivel->level_type->description,
                'costo' => $nivel->price,
                'fInicio' => $nivel->start_date,
                'fFin' => $nivel->end_date,
                'aulas' => array(),
            ];
            foreach ($nivel->classrooms as $clase) {
                $nivelTemp['aulas'][] = (object) [
                    'id' => $clase->id,
                    'nombre' => $clase->name,
                    'vacantes' => $clase->vacancy,
                ];
            }
            $dataNiveles[] = (object) $nivelTemp;
        }
        return $dataNiveles;
    }


    public function informacionNivel(int $nivel_id)
    {
        return Level::join('type_codes', 'levels.type_id', '=', 'type_codes.id')
            ->where('levels.id', $nivel_id)
            ->select([
                'levels.id',
                'levels.period_id',
                'type_codes.description',
                'type_codes.type',
                'levels.start_date',
                'levels.end_date',
                'levels.price',
                'levels.status',
            ])->first();
    }

    public function generarNiveles(int $periodo_id)
    {
        $nivelesGenerados = self::buscarNiveles($periodo_id);
        if (count($nivelesGenerados) == 0) {
            $nivelesAcademicos = Type_code::all();
            foreach ($nivelesAcademicos as $nivel) {
                $nivelGenerado = new Level();
                $nivelGenerado->type_id = $nivel->id;
                $nivelGenerado->period_id = $periodo_id;
                $nivelGenerado->start_date = null;
                $nivelGenerado->end_date = null;
                $nivelGenerado->price = null;
                $nivelGenerado->status = 0;
                $nivelGenerado->save();
            }
        }
        return false;
    }

    public function actualizarNivel(int $nivel_id, object $obj): bool
    {
        $nivel = Level::find($nivel_id);
        if ($nivel) {
            $nivel->start_date = $obj->fInicio;
            $nivel->end_date = $obj->fFin;
            $nivel->price = $obj->costo;
            $nivel->status = $obj->estado;
            $nivel->save();
            return true;
        }
        return false;
    }

    public function cambiarEstado(int $nivel_id, int $estado): bool
    {
        $nivel = Level::find($nivel_id);
        if ($nivel) {
            $nivel->status = $estado;
            return true;
        }
        return false;
    }
}
