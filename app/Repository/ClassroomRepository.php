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

    public function listaEstudiantes(  $clase_id,  $busqueda = null ){
        $busqueda = $busqueda? $busqueda:'';
       return Classroom::join('enrollments', 'classrooms.id', 'enrollments.classroom_id')
                            ->join('students', 'students.id', 'enrollments.student_id' )
                            ->join('entities', 'students.entity_id', 'entities.id')
                            ->where('classrooms.id', $clase_id)
                            ->where('enrollments.deleted_at', null)
                            ->where('classrooms.deleted_at', null)
                            ->where('students.deleted_at', null)
                            ->where('entities.deleted_at', null)
                            ->where( fn( $query )=>$query->where('entities.name', 'like', "%$busqueda%")
                                                        ->orWhere('entities.father_lastname', 'like', "%$busqueda%")
                                                        ->orWhere('entities.mother_lastname', 'like', "%$busqueda%")
                                                        ->orWhere('entities.document_number', 'like', "%$busqueda%") )
                            ->orderBy('entities.father_lastname', 'asc')
                            ->orderBy('entities.mother_lastname', 'asc')
                            ->orderBy('entities.name', 'asc')
                            ->select(
                                'classrooms.id as classroom_id',
                                'enrollments.id as enrollment_id',
                                'students.id as student_id',
                                'entities.id as entity_id',
                                'enrollments.code',
                                'classrooms.name',
                                'entities.father_lastname',
                                'entities.mother_lastname',
                                'entities.name',
                                'entities.address',
                                'entities.document_type',
                                'entities.document_number',
                            )
                            ->get()->toArray();
    }

}
