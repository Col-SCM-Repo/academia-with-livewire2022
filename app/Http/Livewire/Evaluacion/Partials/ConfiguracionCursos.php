<?php

namespace App\Http\Livewire\Evaluacion\Partials;

use Livewire\Component;

class ConfiguracionCursos extends Component
{
    public function __construct()
    {

    }

    protected $listeners = [];
    /*
            'resetear-configuracion-cursos'
    */

    public function render()
    {
        return view('livewire.evaluacion.partials.configuracion-cursos');
    }
}
