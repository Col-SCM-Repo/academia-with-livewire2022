<?php

namespace App\Repository;

use App\Models\School;

class SchoolRepository extends School
{
    private $distritoRepository;
    private $paisRepository;

    public function __construct()
    {
        $this->distritoRepository = new DistrictRepository();
        $this->paisRepository = new CountryRepository();
    }

    public function buscarEscuela($nombreEscuela)
    {
        return School::where('name', $nombreEscuela)->first();
    }

    public function registrarBuscarEscuela($nombreEscuela, $direccion = null, $nombreDistrito = null, $nombrePais = null)
    {
        $escuela = self::buscarEscuela($nombreEscuela);
        if (!$escuela) {

            $distrito = $nombreDistrito ? $this->distritoRepository->registraroBuscarDistrito($nombreDistrito)->id : null;
            $pais = $nombrePais ? $this->paisRepository->registrarBuscarPais($nombrePais)->id : 173;

            $escuela = new School();
            $escuela->name = $nombreEscuela;
            $escuela->address = $direccion;
            $escuela->district_id = $distrito;
            $escuela->country_id = $pais;
            $escuela->save();
        }
        return $escuela;
    }

    public function listarEscuelas()
    {
        return School::all();
    }

    public function eliminarEscuela($nombreEscuela)
    {
        $escuela = self::buscarEscuela($nombreEscuela);
        if ($escuela) {
            $escuela->delete();
            return true;
        }
        return false;
    }
}
