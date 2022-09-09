<?php

namespace App\Enums;

abstract class TiposBecasEnum
{
    const PORCENTUAL_FIJO = 'precentage';
    const PORCENTUAL_DINAMICO = 'precentage_dinamic';
    const MONTO_FIJO = 'static';
    const MONTO_DINAMICO = 'static_dinamic';
    const OTRO = 'other';

    public static function getName( string $value ){
        switch ($value) {
            case self::PORCENTUAL_FIJO : return 'PORCENTUAL FIJO';
            case self::PORCENTUAL_DINAMICO : return 'PORCENTUAL DINAMICO';
            case self::MONTO_FIJO : return 'MONTO FIJO';
            case self::MONTO_DINAMICO : return 'MONTO DINAMICO';
            case self::OTRO : return 'OTRO';
            default: return 'INDEFINIDO';
        }
    }
}
