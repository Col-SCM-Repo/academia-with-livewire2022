<?php

use App\Enums\EstadosAlertas;
use App\Enums\EstadosEntidad;
use Livewire\Component;

function convertArrayUpperCase(array $array, bool $wantObject = true)
{
    $temp = array();
    foreach ($array as $key => $value) {
        $temp[$key] = strtoupper($value);
    }
    return $wantObject ? (object) $temp : $temp;
}

function  openModal( Component $context, string $query, bool $open = true  ){
    $context->emit('change-modal', (object)[
        'query' => $query,
        'estado' => $open? 'show': 'hide'
    ]);
}

function sweetAlert( Component $context, string $nombreEntidad , EstadosEntidad $accion ,EstadosAlertas $tipoAlerta = EstadosAlertas::SUCCESS ){
    $titulo = 'Alerta';
    $mensaje = null;
    $evento = 'sweet-'.$tipoAlerta->value;
    switch($tipoAlerta){
        case EstadosAlertas::SUCCESS :
            $titulo = 'Correcto';
            $mensaje = "El $nombreEntidad fue ".$accion->value."do correctamente";
        break;
        case EstadosAlertas::WARNING :
            $titulo = 'Alerta';
            $mensaje = "No se puede $accion->value"."ar el $nombreEntidad";
        break;
        case EstadosAlertas::ERROR :
            $titulo = 'Error';
            $mensaje = "Error al $accion->value"."ar el $nombreEntidad";
        break;
        default: return ;
    }
    $context->emit( $evento , (object) [ 
        'titulo'=> $titulo , 
        'mensaje' => $mensaje
    ]);
}

function toastAlert( Component $context,string $mensaje, EstadosAlertas $tipoAlerta = EstadosAlertas::ERROR ){
    $titulo = 'Alerta';
    $evento = 'alert-'.$tipoAlerta->value;
    switch($tipoAlerta){
        case EstadosAlertas::SUCCESS :
            $titulo = 'Correcto';
        case EstadosAlertas::WARNING :
            $titulo = 'Alerta';
        break;
        case EstadosAlertas::ERROR :
            $titulo = 'Error';
        break;
        default: return ;
    }
    $context->emit( $evento , (object) [ 
        'titulo'=> $titulo , 
        'mensaje' => $mensaje
    ]);
}

function toastAlertAvanzado( Component $context, string $nombreEntidad , EstadosEntidad $accion ,EstadosAlertas $tipoAlerta = EstadosAlertas::ERROR ){
    $titulo = 'Alerta';
    $mensaje = null;
    $evento = 'alert-'.$tipoAlerta->value;
    switch($tipoAlerta){
        case EstadosAlertas::SUCCESS :
            $titulo = 'Correcto';
            $mensaje = "El $nombreEntidad fue ".$accion->value."do correctamente";
        break;
        case EstadosAlertas::WARNING :
            $titulo = 'Alerta';
            $mensaje = "No se puede $accion->value"."ar el $nombreEntidad";
        break;
        case EstadosAlertas::ERROR :
            $titulo = 'Error';
            $mensaje = "Error al $accion->value"."ar el $nombreEntidad";
        break;
        default: return ;
    }
    $context->emit( $evento , (object) [ 
        'titulo'=> $titulo , 
        'mensaje' => $mensaje
    ]);
}
