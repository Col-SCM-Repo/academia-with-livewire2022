<?php

namespace App\Enums;

abstract class TiposCuotaEnum
{
    const MATRICULA = 'enrollment';
    const CICLO = 'installment';

    public static function getName( string $tipo_cuota  ){
        switch ($tipo_cuota) {
            case SelF::MATRICULA: return 'MATRICULA';
            case SelF::CICLO: return 'CICLO';
            default:    return 'INDEFINIDO';
        }
    }
}
