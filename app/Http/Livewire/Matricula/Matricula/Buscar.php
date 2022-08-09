<?php

namespace App\Http\Livewire\Matricula\Matricula;

use Livewire\Component;

class Buscar extends Component
{
    public $search = "";
    public $data = [];
    public function render()
    {
        return view('livewire.matricula.matricula.buscar');
    }
}
