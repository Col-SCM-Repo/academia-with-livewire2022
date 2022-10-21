<?php

namespace App\Enums;

abstract class TiposUsuariosEnum
{
    const ADMINISTRADOR  = 'admin';
    const SECRETARIA  =  'secretary';

    public static function getName( string $tipo_usuario ){
        switch ( $tipo_usuario ) {
            case 'admin': return "ADMINISTRADOR";
            case 'secretary': return "SECRETARIA";
            default: return "NO IDENTIFICADO";
        }
    }

    /* public static function toArray(){
        return [ self::ADMINISTRADOR, self::SECRETARIA ];
    } */

}

/*
enum EstadosAlertas
{
    case SUCCESS  = 'success';
    case WARNING  =  'warning';
    case ERROR  = 'error';

    public static function getEstado(self $value): string
    {
        return match ($value) {
            EstadosAlertas::SUCCESS => 'correcto',
            EstadosAlertas::WARNING => 'peligro',
            EstadosAlertas::ERROR => 'error',
        };
    }
    // name es el nombre y value es el valor que tine
    // EstadosAlertas::getEstado(EstadosAlertas::SUCCESS)

}
 */
