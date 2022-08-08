<?php

namespace App\Repository;

use App\Models\Occupation;

class OccupationRepository extends Occupation
{
    public function buscarOcupacion($nombreOcupacion)
    {
        return Occupation::where('name', $nombreOcupacion)->first();
    }

    public function registrarBuscarOcupacion($nombreOcupacion)
    {
        $Ocupacion = self::buscarOcupacion($nombreOcupacion);
        if (!$Ocupacion) {
            $Ocupacion = new Occupation();
            $Ocupacion->name = $nombreOcupacion;
            $Ocupacion->save();
        }
        return $Ocupacion;
    }

    public function eliminarOcupacion($nombreOcupacion)
    {
        $Ocupacion = self::buscarOcupacion($nombreOcupacion);
        if ($Ocupacion) {
            $Ocupacion->delete();
            return true;
        }
        return false;
    }
}
