<?php

namespace App\Enums;

abstract class ModoPagoEnum
{
    const EFECTIVO  = 'cash';                   // El elemento esta activado o existe
    const DEPOSITO_BANCARIO  = 'deposit';       // El elemento esta inactivo o eliminado

    public static function getName( string $nombre_modo ){
        switch ($nombre_modo) {
            case self::EFECTIVO:            return "EFECTIVO";
            case self::DEPOSITO_BANCARIO:   return "DEPOSITOBANCARIO";
            default:                        return "INDETERMINADO";
        }
    }
}
