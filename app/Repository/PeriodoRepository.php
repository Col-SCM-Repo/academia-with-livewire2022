<?php

namespace App\Repository;

use App\Models\Period;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class PeriodoRepository extends Period
{
    private $_nivelRepository;

    public function __construct()
    {
        $this->_nivelRepository = new LevelRepository();
    }

    public function builderModelRepository()
    {
        return (object) [
            'id' => null,
            'nombre' => '', //name
            'anio' => null, //year
            'activo' => 1,  //active - 1 es activo y  0 es inactivo
        ];
    }

    public function buscarPeriodo(string $nombrePeriodo)
    {
        return Period::where('name', $nombrePeriodo)->first();
    }

    public function registrarPeriodo(object $obj)
    {
        Log::debug("eNTRO A REGISTRAR PERIODO");
        $periodo = self::buscarPeriodo($obj->nombre);
        if (!$periodo) {
            $periodo = new Period();
            $periodo->name = $obj->nombre;
            $periodo->year = $obj->anio;
            $periodo->active = $obj->activo;
            $periodo->save();
            $this->_nivelRepository->generarNiveles($periodo->id);
        }
        return $periodo;
    }

    public function actualizarPeriodo(int $periodo_id, object $obj)
    {
        $periodo = Period::find($periodo_id);
        if ($periodo) {
            $periodo->name = $obj->nombre;
            $periodo->year = $obj->anio;
            $periodo->active = $obj->activo;
            $periodo->save();
        }
        return $periodo;
    }

    public function cambiarEstado(int $idPeriodo, int $estado)
    {
        $periodo = Period::find($idPeriodo);
        if ($periodo) {
            $periodo->active = $estado;
            $periodo->save();
            return true;
        }
        return false;
    }

    public function listaPeriodos(int $estado = null)
    {
        if ($estado)
            return Period::where('active', $estado)->orderBy('year', 'DESC')->get();
        else
            return Period::orderBy('year', 'DESC')->get();
    }

    public function cicloVigente(int $anio_id = null)
    {
        if ($anio_id) {
            return Period::find($anio_id);
        }
        $cicloVigente = Period::orderBy('year', 'DESC')->where('active', 1)->where('deleted_at', null)->first();
        if ($cicloVigente)
            return $cicloVigente;
        return Period::orderBy('year', 'DESC')->first();
    }

    public function eliminarPeriodo(int $periodod_id)
    {
        $periodoEliminar = self::find($periodod_id);
        if ($periodoEliminar) {
            $periodoEliminar->delete();
            return true;
        }
        return false;
    }
    /*
    public function getPeriodoEnrollment($id)
    {
        return Period::with(['levels.enrollments', 'levels.classrooms.enrollments', 'levels.level_type'])->whereId($id)->firstOrFail();
    }

    // Devuelve informacion de todos los periodos activos
    public function getPeriodosAndLevels()
    {
        return Period::with('classrooms.level.level_type')->where('active', 1)->get();
    }

    // recibe el ID una clase y muestra informacion de los alumnos y sus apoderados
    public function getStudentsAndParents($id)
    {
        return Classroom::with('level.level_type', 'enrollments.student.entity', 'enrollments.relative.entity')->whereId($id)->firstOrFail();
    }
*/
}
