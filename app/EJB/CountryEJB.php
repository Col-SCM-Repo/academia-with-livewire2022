<?php

namespace App\EJB;

use App\Models\Country;

class CountryEJB extends Country
{
    public function buscarPais($param)
    {
        return Country::where('name', $param)
            ->orWhere('code', $param)
            ->firts();
    }

    public function registrarPais($nombre, $codigoPais)
    {
        $pais = self::buscarPais($nombre);
        if (!$pais) {
            $pais = new Country();
            $pais->name = $nombre;
            $pais->code = $codigoPais;
            $pais->save();
        }
        return $pais;
    }

    public function obtenerIdPais($nombre)
    {
        // Solo puede encontrar el ID del pais (no crea)
        $pais = self::buscarPais($nombre);
        return $pais ? $pais->id : null;
    }

    public function listaPaises()
    {
        return Country::all();
    }
}
