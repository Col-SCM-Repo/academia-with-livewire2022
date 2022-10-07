<?php

namespace App\Enums;

abstract class TiposEstudianteEnum
{
    const LIBRE = 'free';
    const ESTUDIANTE = 'student';

    public static function getName( string $tipo_estudiante  ){
        switch ($tipo_estudiante) {
            case SelF::LIBRE: return 'LIBRE';
            case SelF::ESTUDIANTE: return 'ESTUDIANTE';
            default:    return 'NO DEFINIDO';
        }
    }
}



