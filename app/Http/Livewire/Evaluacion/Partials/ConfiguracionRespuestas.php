<?php

namespace App\Http\Livewire\Evaluacion\Partials;

use Livewire\Component;

class ConfiguracionRespuestas extends Component
{
    public function __construct()
    {

    }

    protected $listeners = [];
    /*
            'resetear-configuracion-respuestas'
    */

    public function render()
    {
        return view('livewire.evaluacion.partials.configuracion-respuestas');
    }
}
