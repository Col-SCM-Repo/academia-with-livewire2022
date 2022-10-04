<?php

namespace App\Enums;

abstract class TipoEvaluacionEnum
{
    const SIMULACRO = 'simulacrum';
    const MENSUAL = 'monthly';
    const SEMANAL = 'weekly';
    const DIARIO = 'daily';
    const RAPIDO = 'quick';
    const OTRO = 'other';

    public static function getArrayTypesEvaluation(){
        return [
            self::SIMULACRO,
            self::MENSUAL,
            self::SEMANAL,
            self::DIARIO,
            self::RAPIDO,
            self::OTRO,
        ];
    }

    public static function getName( string $value ){
        switch ($value) {
            case self::SIMULACRO : return 'SIMULACRO';
            case self::MENSUAL : return 'MENSUAL';
            case self::SEMANAL : return 'SEMANAL';
            case self::DIARIO : return 'DIARIO';
            case self::RAPIDO : return 'RAPIDO';
            case self::OTRO : return 'OTRO';

            default: return 'INDEFINIDO';
        }
    }
}
