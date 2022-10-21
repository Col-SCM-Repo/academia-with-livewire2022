<?php

namespace App\Repository;

use App\Enums\EstadosEnum;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use DB;
use Exception;

class UsersRepository extends User
{
    public function __construct()
    {

    }

    public function buildModelUser(){
        return (object)[
            /* 'nombre' => null, */       // name
            'entidad_id' => null,         // entity_id
            'usuario' => null,            // username
            'correo_electronico' => null, // email
            'tipo_usuario' => null,       // type
            'contrasenia' => null,        // password
            'estado' => null,             // status
        ];
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

    public function registrar( object $modeloUser ){
        if(self::buscarDuplicados($modeloUser->usuario))   throw new Exception('Error, usuario duplicado');
        $usuario = new User();
        $usuario->entity_id = $modeloUser->entidad_id;
        $usuario->username = $modeloUser->usuario;
        $usuario->email = $modeloUser->correo_electronico;
        $usuario->type = $modeloUser->tipo_usuario;
        $usuario->status = $modeloUser->estado;
        $usuario->save();
        self::actualizarContaseña( $modeloUser->contrasenia, $usuario);
        return $usuario;
    }

    public function actualizar(int $usuario_id, object $modeloUser ){
        $usuario = self::find($usuario_id);
        if(!$usuario) throw new Exception('Error, no se encontró al usuario');
        $usuario->username = $modeloUser->usuario;
        $usuario->email = $modeloUser->correo_electronico;
        $usuario->type = $modeloUser->tipo_usuario;
        $usuario->status = $modeloUser->estado;
        $usuario->save();
        return $usuario;
    }

    public function actualizarContaseña( $password, $usuario=null, $usuario_id=null  ){
        $password_encrypted = Hash::make($password);
        if($usuario_id){
            $usuario = self::find($usuario_id);
            if(!$usuario) throw new Exception('Error, no se encontró al usuario. ');
        }
        $usuario->password = $password_encrypted;
        $usuario->save();
        return $password_encrypted;
    }

    public function buscarDuplicados ( $nombre_usuario ){
        $usuariosDuplicados = self::where('username', $nombre_usuario)->get();
        return count($usuariosDuplicados)>0;
    }

    public function eliminar( int $usuario_id ){
        $usuario = self::find($usuario_id);
        if(!$usuario) throw new Exception("No se encontro el usuario con codigo [$usuario_id] ");
        $usuario->delete();
    }

}
