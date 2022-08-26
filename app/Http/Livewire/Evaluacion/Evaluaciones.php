<?php

namespace App\Http\Livewire\Evaluacion;

use Livewire\Component;

class Evaluaciones extends Component
{
    public $examen_id;

    public function render()
    {
        return view('livewire.evaluacion.evaluaciones');
    }

    public function openModalExamenes(){
        openModal($this, "#form-modal-examenes");
    }
}
