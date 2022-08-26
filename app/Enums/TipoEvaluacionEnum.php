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
}
