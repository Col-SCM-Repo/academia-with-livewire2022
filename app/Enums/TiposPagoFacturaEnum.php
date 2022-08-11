<?php

namespace App\Enums;

abstract class TiposPagoFacturaEnum
{
    const TICKET  = 'ticket';           // pago correcto
    const DEVOLUCION  = 'note';         // pago restaurado
}
