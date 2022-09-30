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
        'resetear-configuracion-respuestas' => 'initialState'
    ];

    public function initialState(){
        $this->reset(['examen_id']);
    }

    public function render()
    {
        toastAlert($this, 'Renderizando partials.configuracion-respuestas', 'warning');
        return view('livewire.evaluacion.partials.configuracion-respuestas');
    }


    public function renderizar( $examen_id ){
        $this->examen_id = $examen_id;

    }
}
