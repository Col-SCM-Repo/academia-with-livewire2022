<?php

namespace App\Repository;

use App\Models\Country;

class CountryRepository extends Country
{
    public function buscarPais($nombrePais)
    {
        return Country::where('name', $nombrePais)
            ->orWhere('code', $nombrePais)
            ->firts();
    }

    public function registrarBuscarPais($nombrePais, $codigoPais = "-")
    {
        $pais = self::buscarPais($nombrePais);
        if (!$pais) {
            $pais = new Country();
            $pais->name = $nombrePais;
            $pais->code = $codigoPais;
            $pais->save();
        }
        return $pais;
    }

    public function obtenerIdPais($nombrePais)
    {
        // Solo puede encontrar el ID del pais (no crea)
        $pais = self::buscarPais($nombrePais);
        return $pais ? $pais->id : null;
    }

    public function listaPaises()
    {
        return Country::all();
    }
}
