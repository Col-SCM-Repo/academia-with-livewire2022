<?php
namespace App\Enums;

enum EstadosEntidad : string {
    case CREATED  = 'crea'; // do / ar
    case UPDATED  = 'actualiza'; // do / ar
    case DELETED  = 'elimina';// do / ar

    public static function getEstado ( self $value ) : string{
        return match ($value) {
            EstadosEntidad::CREATED => 'creado',
            EstadosEntidad::UPDATED => 'actualizado',
            EstadosEntidad::DELETED => 'eliminado'
        };
    }
} 




