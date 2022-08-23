<?php

namespace App\Repository;

use App\Models\Career;
use DB;

class CareerRepository extends Career
{
    private $_grupoRepository;

    public function __construct()
    {
        $this->_grupoRepository = new GroupRepository();
    }

    public function buscarCarrera($nombreCarrera)
    {
        return Career::where('career', $nombreCarrera)->first();
    }

    public function buscarRegistrarCarrera($nombreCarrera, $nemonico = null, $nombreGrupo = 'O')
    {
        $carrera = self::buscarCarrera($nombreCarrera);
        if (!$carrera) {
            $grupo = $this->_grupoRepository->buscarGrupo($nombreGrupo);
            $carrera->group_id = $grupo->id;
            $carrera->career = $nombreCarrera;
            $carrera->nmonico = $nemonico ? $nemonico : substr($nombreCarrera, 0, 2) ;
            $carrera->save();
        }
        return $carrera;
    }

    public function listarCarreras()
    {
        return DB::table('careers')->select('id', 'career as name')->get();
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
