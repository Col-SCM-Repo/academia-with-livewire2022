<?php

namespace App\Repository;

use App\Enums\EstadosEnum;
use App\Models\User;
use DB;

class UsersRepository extends User
{
    private $_grupoRepository;

    public function __construct()
    {
    }

    public function getListaUsuarios(){
        $usuarios = [];
        foreach (self::all() as $key => $usuario)
            $usuarios[ $key ] = [
                'usuario_id' => $usuario->id,
                'nombres' => $usuario->entity->full_name(),
                'dni' => $usuario->entity->document_number,
                'tipo' => $usuario->type,
                'estado' => EstadosEnum::getName($usuario->status)
            ];
        return $usuarios;
    }

}
