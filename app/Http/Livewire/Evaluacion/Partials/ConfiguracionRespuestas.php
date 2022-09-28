<?php

namespace App\Http\Livewire\Evaluacion\Partials;

use Livewire\Component;

class ConfiguracionRespuestas extends Component
{
    public  $examen_id;

    public function __construct()
    {

    }

    protected $listeners = [
        'renderizar' => 'renderizar',
    ];
    /*
            'resetear-configuracion-respuestas'
    */

    public function render()
    {
        toastAlert($this, 'Renderizando partials.configuracion-respuestas', 'warning');
        return view('livewire.evaluacion.partials.configuracion-respuestas');
    }


    public function renderizar( $examen_id ){
        $this->examen_id = $examen_id;

    }
}
