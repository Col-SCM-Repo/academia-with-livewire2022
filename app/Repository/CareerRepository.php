<?php

namespace App\Repository;

use App\Models\Career;

class CareerRepository extends Career
{
    private $_grupoRepository;

    public function __construct()
    {
        $this->_grupoRepository = new GroupRepository();
    }

    public function buscarCarrera($nombreCarrera)
    {
        return Career::where('carrer', $nombreCarrera)->first();
    }

    public function registrarCarrera($nombreCarrera, $nemonico, $nombreGrupo = 'O')
    {
        $carrera = self::buscarCarrera($nombreCarrera);
        if (!$carrera) {
            $grupo = $this->_grupoRepository->buscarGrupo($nombreGrupo);
            $carrera->group_id = $grupo->id;
            $carrera->career = $nombreCarrera;
            $carrera->nmonico = $nemonico;
            $carrera->save();
        }
        return $carrera;
    }

    public function listarCarreras()
    {
        return Career::all();
    }

    public function eliminarCarrera($nombreCarrera)
    {
        $carrera = self::buscarCarrera($nombreCarrera);
        if ($carrera) {
            $carrera->delete();
            return true;
        }
        return false;
    }

    public function modificarCarrera($idCarrera, string $carrera)
    {
        $carrera = Career::find($idCarrera);
        if ($carrera) {
            $carrera->delete();
            return true;
        }
        return null;
    }
}
