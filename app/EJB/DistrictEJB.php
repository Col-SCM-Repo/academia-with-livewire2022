<?php

namespace App\EJB;

use App\Models\District;

class DistrictEJB extends District
{

    private $provinciaEJB;

    public function __construct()
    {
        $this->provinciaEJB = new ProvinceEJB();
    }

    public function buscarDistrito($nombreDistrito)
    {
        return District::where('name', $nombreDistrito)->first();
    }

    public function registraroBuscarDistrito($nombreDistrito, $nombreProvincia = null)
    {
        $distrito = self::buscarDistrito($nombreDistrito);
        if (!$distrito) {
            $provincia  = $this->provinciaEJB->buscarProvincia($nombreProvincia);
            // la provincia debe existir
            if (!$provincia) return null;
            $distrito = new District();
            $distrito->name = $nombreDistrito;
            $distrito->province_id = $provincia->id;
            $distrito->save();
        }
        return $distrito;
    }

    public function listaDistritos()
    {
        return District::all();
    }

    public function eliminarDistrito($nombreDistrito)
    {
        $distrito = self::buscarDistrito($nombreDistrito);
        if ($distrito) {
            $distrito->delete();
            return true;
        }
        return false;
    }
}
