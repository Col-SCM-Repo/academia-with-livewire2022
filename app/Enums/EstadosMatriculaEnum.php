<?php

namespace App\Enums;

abstract class EstadosMatriculaEnum
{
    const ACTIVO  = 1;                  // El estudiante esta matriculado correctamente
    const INACTIVO  = 0;                // El estudiante esta matriculado pero sin generar pagos
    const PENDIENTE_ACTIVACION  = -1;   // El estudiante esta matriculado pero requiere actualizar pagos

    public static function getEstado( int $estadoMatricula ){
        switch ($estadoMatricula) {
            case self::ACTIVO: return 'ACTIVO';
            case self::INACTIVO: return 'INACTIVO';
            case self::PENDIENTE_ACTIVACION: return 'REQUIERE ACTUALIZAR PAGOS';
            default: return 'ESTADO INDEFINIDO';
        }
    }
}
