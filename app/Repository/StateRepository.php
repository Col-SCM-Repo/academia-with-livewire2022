<?php

namespace App\Repository;

use App\Models\State;

class StateRepository extends State
{
    public function buscarDepartamento($nombreDepartamento)
    {
        return State::where('name', $nombreDepartamento)->firts();
    }

    public function registrarDepartamento($nombreDepartamento)
    {
        $departamento = self::buscarDepartamento($nombreDepartamento);
        if (!$departamento) {
            $departamento = new State();
            $departamento->name = $nombreDepartamento;
            $departamento->save();
        }
        return $departamento;
    }

    public function listaDepartamento()
    {
        return State::all();
    }

    public function eliminarDepartamento($nombreDepartamento)
    {
        $departamento = self::buscarDepartamento($nombreDepartamento);
        if ($departamento) {
            $departamento->delete();
            return true;
        }
        return false;
    }

    public function obtenerIdDepartamento($nombreDepartamento)
    {
        $departamento = self::buscarDepartamento($nombreDepartamento);
        if (!$departamento)
            $departamento = self::registrarDepartamento($nombreDepartamento);
        return $departamento->id;
    }
}
