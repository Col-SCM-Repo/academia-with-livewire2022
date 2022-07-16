<?php

namespace App\EJB;

use App\Models\School;

class SchoolEJB extends School
{
    private $distritoEJB;
    private $paisEJB;

    public function __construct()
    {
        $this->distritoEJB = new DistrictEJB();
        $this->paisEJB = new CountryEJB();
    }

    public function buscarEscuela($nombreEscuela)
    {
        return School::where('name', $nombreEscuela)->first();
    }

    public function registrarEscuela($nombreEscuela, $direccion, $nombreDistrito, $nombrePais)
    {
        $escuela = self::buscarEscuela($nombreEscuela);
        if (!$escuela) {
            $distrito = $this->distritoEJB->buscarDistrito($nombreDistrito);
            $pais = $this->paisEJB->buscarPais($nombrePais);

            if (!($distrito && $pais)) return null;

            $escuela = new School();
            $escuela->name = $nombreEscuela;
            $escuela->address = $direccion;
            $escuela->district_id = $distrito->id;
            $escuela->country_id = $pais->id;
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
