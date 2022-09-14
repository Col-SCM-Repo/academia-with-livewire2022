<?php

namespace App\Enums;

abstract class TipoDescuentosEnum
{
    const PORCENTAJE = 'percentaje';
    const MONTO_FIJO = 'fixed';
    const OTRO = 'other';

    public static function getName( string $value ){
        switch ($value) {
            case self::PORCENTAJE : return 'PORCENTUAL';
            case self::MONTO_FIJO : return 'MONTO FIJO';
            case self::OTRO : return 'OTRO';
            default: return 'INDEFINIDO';
        }
    }
}
