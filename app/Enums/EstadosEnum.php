<?php

namespace App\Enums;

abstract class EstadosEnum
{
    const ACTIVO  = 1;          // El elemento esta activado o existe
    const INACTIVO  = 0;        // El elemento esta inactivo o eliminado
    const NONE  = null;         // do / ar
}
