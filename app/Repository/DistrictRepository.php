<?php

namespace App\Repository;

use App\Models\District;

class DistrictRepository extends District
{

    private $provinciaRepository;

    public function __construct()
    {
        $this->provinciaRepository = new ProvinceRepository();
    }

    public function buscarDistrito($nombreDistrito)
    {
        return District::where('name', $nombreDistrito)->first();
    }

    public function registraroBuscarDistrito($nombreDistrito, $nombreProvincia = null)
    {
        $distrito = self::buscarDistrito($nombreDistrito);
        if (!$distrito) {
            $provincia  = $nombreProvincia ? $this->provinciaRepository->buscarProvincia($nombreProvincia) : null;
            // la provincia debe existir
            $distrito = new District();
            $distrito->name = $nombreDistrito;
            $distrito->province_id = $provincia ? $provincia->id : null;
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
