<?php

namespace App\Repository;

use App\Models\Province;

class ProvinceRepository extends Province
{
    private $departamentoRepository;

    public function __construct()
    {
        $this->departamentoRepository = new StateRepository();
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
            $provincia->state_id = $this->departamentoRepository->obtenerIdDepartamento($nombreDepartamento);
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
