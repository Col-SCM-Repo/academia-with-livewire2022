<?php
namespace App\Enums;

enum EstadosAlertas : string {
    case SUCCESS  = 'success';
    case WARNING  =  'warning';
    case ERROR  = 'error';

    public static function getEstado ( self $value ) : string{
        return match ($value) {
            EstadosAlertas::SUCCESS => 'correcto',
            EstadosAlertas::WARNING => 'peligro',
            EstadosAlertas::ERROR => 'error',
        };
    }
    // name es el nombre y value es el valor que tine
    // EstadosAlertas::getEstado(EstadosAlertas::SUCCESS)

} 




