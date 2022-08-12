<?php

namespace App\Repository;

use App\Models\Classroom;
use App\Models\Level;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class ClassroomRepository extends Classroom
{
    private $_nivelRepository, $_periodoRepository;

    public function __construct()
    {
        $this->_nivelRepository = new LevelRepository();
        $this->_periodoRepository = new PeriodoRepository();
    }

    public function builderModelRepository()
    {
        return (object) [
            'id' => null,
            'nombre' => null,       // name
            'nivel_id' => null,     // level_id
            'vacantes' => null,     // vacancy
        ];
    }

    public function getInformacionClase(int $clase_id)
    {
        $clase = Classroom::find($clase_id);
        if ($clase) {
            return (object) [
                'id' => $clase->id,
                'nombre' => $clase->name,
                'nivel_id' => $clase->level_id,
                'nivel' => $clase->level->level_type->description,
                'vacantes' => $clase->vacancy,
            ];
        } else
            return null;
    }

    public function getClasesPorNivel(int $nivel_id)
    {
        $nivel = $this->_nivelRepository->getNivelPorId($nivel_id);
        if ($nivel) {
            $clases = array();
            foreach ($nivel->classrooms as $clase) {
                $clases[] = self::getInformacionClase($clase->id);
            }
            return count($nivel->classrooms) > 0 ? $clases : null;
        } else
            return null;
    }

    public function getListaClases(int $periodo_id)
    {
        $periodo = $this->_periodoRepository::find($periodo_id);
        if (!$periodo) throw new NotFoundResourceException('No se encontro el periodo indicado');

        $listaAulas = array();
        foreach ($periodo->levels as $nivel) {
            foreach ($nivel->classrooms as $aula) {
                $alumnosMatriculados = count($aula->enrollments);
                $listaAulas[$aula->id] = (object)[
                    'aula_id' =>  $aula->id,
                    'nombre' =>  $periodo->name . '/' . $nivel->level_type->description . '/' . $aula->name,
                    'ciclo' =>  $periodo->name,
                    'nivel' =>  $nivel->level_type->description,
                    'aula' =>  $aula->name,
                    'costo' => $nivel->price,
                    'total_vacantes' => $aula->vacancy,
                    'vacantes_ocupadas' => $alumnosMatriculados,
                    'vacantes_disponibles' => $aula->vacancy - $alumnosMatriculados,
                ];
            }
        }
        return $listaAulas;
    }

    public function registrarClase(object $obj)
    {
        $claseNueva = new Classroom();
        $claseNueva->name = $obj->nombre;
        $claseNueva->level_id = $obj->nivel_id;
        $claseNueva->vacancy = $obj->vacantes;
        $claseNueva->save();
        return $claseNueva;
    }

    public function actualizarClase(int $clase_id, object $obj)
    {
        $claseActualizar = Classroom::find($clase_id);
        if ($claseActualizar) {
            $claseActualizar->name = $obj->nombre;
            $claseActualizar->vacancy = $obj->vacantes;
            $claseActualizar->save();
            return $claseActualizar;
        }
        return null;
    }

    public function eliminarClase(int $clase_id)
    {
        $claseEliminar = Classroom::find($clase_id);
        if ($claseEliminar) {
            $claseEliminar->delete();
            return true;
        }
        return false;
    }
}
