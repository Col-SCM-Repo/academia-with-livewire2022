<?php

namespace App\Enums;

abstract class TiposBecasEnum
{
    const PORCENTUAL_FIJO = 'precentage';
    const PORCENTUAL_DINAMICO = 'precentage_dinamic';
    const MONTO_FIJO = 'static';
    const OTRO = 'other';

    public static function getName( string $value ){
        switch ($value) {
            case self::PORCENTUAL_FIJO : return 'PORCENTUAL_FIJO';
            case self::PORCENTUAL_DINAMICO : return 'PORCENTUAL_DINAMICO';
            case self::MONTO_FIJO : return 'MONTO_FIJO';
            case self::OTRO : return 'OTRO';
            default: return 'INDEFINIDO';
        }
    }
}
