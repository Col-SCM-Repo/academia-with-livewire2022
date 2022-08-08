<?php

use App\Enums\EstadosAlertasEnum;
use App\Enums\EstadosEntidadEnum;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

function convertArrayUpperCase(array $array, bool $wantObject = true)
{
    Log::debug("inicio conversion a array");
    $temp = array();
    foreach ($array as $key => $value) {
        $temp[$key] = strtoupper($value);
    }
    return $wantObject ? (object) $temp : $temp;
}

function  openModal(Component $context, string $query, bool $open = true)
{
    $context->emit('change-modal', (object)[
        'query' => $query,
        'estado' => $open ? 'show' : 'hide'
    ]);
}

function sweetAlert(Component $context, string $nombreEntidad, string $accion, string $tipoAlerta = EstadosAlertasEnum::SUCCESS)
{
    $titulo = 'Alerta';
    $mensaje = null;
    $evento = 'sweet-' . $tipoAlerta;
    switch ($tipoAlerta) {
        case EstadosAlertasEnum::SUCCESS:
            $titulo = 'Correcto';
            $mensaje = "El $nombreEntidad fue " . $accion . "do correctamente";
            break;
        case EstadosAlertasEnum::WARNING:
            $titulo = 'Alerta';
            $mensaje = "No se puede $accion" . "ar el $nombreEntidad";
            break;
        case EstadosAlertasEnum::ERROR:
            $titulo = 'Error';
            $mensaje = "Error al $accion" . "ar el $nombreEntidad";
            break;
        default:
            return;
    }
    $context->emit($evento, (object) [
        'titulo' => $titulo,
        'mensaje' => $mensaje
    ]);
}

function toastAlert(Component $context, string $mensaje, string $tipoAlerta = EstadosAlertasEnum::ERROR)
{
    $titulo = 'Alerta';
    $evento = 'alert-' . $tipoAlerta;
    switch ($tipoAlerta) {
        case EstadosAlertasEnum::SUCCESS:
            $titulo = 'Correcto';
        case EstadosAlertasEnum::WARNING:
            $titulo = 'Alerta';
            break;
        case EstadosAlertasEnum::ERROR:
            $titulo = 'Error';
            break;
        default:
            return;
    }
    $context->emit($evento, (object) [
        'titulo' => $titulo,
        'mensaje' => $mensaje
    ]);
}

function toastAlertAvanzado(Component $context, string $nombreEntidad, string $accion, string $tipoAlerta = EstadosAlertasEnum::ERROR)
{
    $titulo = 'Alerta';
    $mensaje = null;
    $evento = 'alert-' . $tipoAlerta;
    switch ($tipoAlerta) {
        case EstadosAlertasEnum::SUCCESS:
            $titulo = 'Correcto';
            $mensaje = "El $nombreEntidad fue " . $accion . "do correctamente";
            break;
        case EstadosAlertasEnum::WARNING:
            $titulo = 'Alerta';
            $mensaje = "No se puede $accion" . "ar el $nombreEntidad";
            break;
        case EstadosAlertasEnum::ERROR:
            $titulo = 'Error';
            $mensaje = "Error al $accion" . "ar el $nombreEntidad";
            break;
        default:
            return;
    }
    $context->emit($evento, (object) [
        'titulo' => $titulo,
        'mensaje' => $mensaje
    ]);
}
