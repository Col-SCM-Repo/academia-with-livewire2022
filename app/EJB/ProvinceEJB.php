<?php

namespace App\EJB;

use App\Models\Province;

class ProvinceEJB extends Province
{
    private $departamentoEJB;

    public function __construct()
    {
        $this->departamentoEJB = new StateEJB();
    }

    public function buscarProvincia($nombreProvincia)
    {
        return Province::where('name', $nombreProvincia)->first();
    }

    public function registrarProvincia($nombreProvincia, $nombreDepartamento)
    {
        $provincia = self::buscarProvincia($nombreProvincia);
        if (!$provincia) {
            $provincia = new Province();
            $provincia->name = $nombreProvincia;
            $provincia->state_id = $this->departamentoEJB->obtenerIdDepartamento($nombreDepartamento);
            $provincia->save();
        }
        return $provincia;
    }

    public function listaProvincias()
    {
        return Province::all();
    }

    public function eliminarProvincia($nombreProvincia)
    {
        $provincia = self::buscarProvincia($nombreProvincia);
        if ($provincia) {
            $provincia->delete();
            return true;
        }
        return false;
    }
}
