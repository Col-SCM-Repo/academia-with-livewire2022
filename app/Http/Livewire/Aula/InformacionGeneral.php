<?php

namespace App\Http\Livewire\Aula;

use Livewire\Component;

class InformacionGeneral extends Component
{
    public $aulaId;

    public function mount( $aula_id ){
        $this->aulaId = $aula_id;
    }

    public function render()
    {
        return view('livewire.aula.informacion-general');
    }
}
