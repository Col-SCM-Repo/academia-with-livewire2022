<?php

namespace App\Repository;

use App\Models\Classroom;
use App\Models\Level;

class ClassroomRepository extends Classroom
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
            'nombre' => null,       // name
            'nivel_id' => null,     // level_id
            'vacantes' => null,     // vacancy
        ];
    }

    public function getInformacionClase ( int $clase_id ) : object | null{
        $clase = Classroom::find( $clase_id );
        if($clase){
            return (object) [
                'id' => $clase->id, 
                'nombre' => $clase->name, 
                'nivel_id' => $clase->level_id, 
                'nivel' => $clase->level->level_type->description, 
                'vacantes' => $clase->vacancy, 
            ];
        }else
            return null;
    }

    public function getClasesPorNivel ( int $nivel_id ) : object | null{
        $nivel = $this->_nivelRepository->getNivelPorId($nivel_id);
        if($nivel){
            $clases = array();
            foreach( $nivel->classrooms as $clase ){
                $clases[] = self::getInformacionClase( $clase->id );
            }
            return count($nivel->classrooms)>0? $clases : null;
        }else
            return null;
    }

    public function registrarClase ( object $obj ) : Classroom {
        $claseNueva = new Classroom();
        $claseNueva->name = $obj->nombre;
        $claseNueva->level_id = $obj->nivel_id;
        $claseNueva->vacancy = $obj->vacantes;
        $claseNueva->save();
        return $claseNueva;
    }

    public function actualizarClase ( int $clase_id, object $obj ) : Classroom | null {
        $claseActualizar = Classroom::find($clase_id);
        if($claseActualizar){
            $claseActualizar->name = $obj->nombre;
            $claseActualizar->vacancy = $obj->vacantes;
            $claseActualizar->save();
            return $claseActualizar;
        }
        return null;
    }

    public function eliminarClase ( int $clase_id ) : bool {
        $claseEliminar = Classroom::find($clase_id);
        if($claseEliminar){
            $claseEliminar->delete();
            return true;
        }
        return false;
    }
}
