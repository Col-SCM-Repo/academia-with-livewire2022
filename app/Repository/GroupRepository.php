<?php

namespace App\Repository;

use App\Models\Group;

class GroupRepository extends Group
{
    public function buscarGrupo($nombreGrupo)
    {
        return Group::where('description', $nombreGrupo)->first();
    }

    public function registrarGrupo($nombreGrupo)
    {
        $grupo = self::buscarGrupo($nombreGrupo);
        if (!$grupo) {
            $grupo = new Group();
            $grupo->description = $nombreGrupo;
            $grupo->save();
        }
        return $grupo;
    }

    public function listarGrupos()
    {
        return Group::all();
    }

    public function eliminarGrupo($nombreGrupo)
    {
        $grupo = self::buscarGrupo($nombreGrupo);
        if ($grupo) {
            $grupo->delete();
            return true;
        }
        return false;
    }
}
