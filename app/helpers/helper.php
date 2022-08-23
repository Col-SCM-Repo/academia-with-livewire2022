<?php

use App\Enums\EstadosAlertasEnum;
use App\Enums\EstadosEntidadEnum;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

function convertArrayUpperCase(array $array, bool $wantObject = true)
{
    $temp = array();
    foreach ($array as $key => $value) {
        $temp[$key] = is_string($value) ? strtoupper($value) : $value;
    }
    return $wantObject ? (object) $temp : $temp;
}

function formatInputStr( string $inputStr ){
    if(is_string($inputStr)){
        return strtoupper(trim($inputStr));
    }
    return $inputStr;
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

function generate_random_password()
{
    //Se define una cadena de caractares.
    $cadena = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";

    $longitudCadena = strlen($cadena);
    $pass = "";
    $longitudPass = 10;
    //Creamos la contraseña
    for ($i = 1; $i <= $longitudPass; $i++) {
        $pos = rand(0, $longitudCadena - 1);
        $pass .= substr($cadena, $pos, 1);
    }
    return $pass;
}
