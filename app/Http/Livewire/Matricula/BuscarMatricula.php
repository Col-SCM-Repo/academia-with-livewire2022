<?php

namespace App\Http\Livewire\Matricula;

use Livewire\Component;

class BuscarMatricula extends Component
{
    public $search = "";
    public $data = [];
    public function render()
    {
        return view('livewire.matricula.buscar-matricula');
    }
}
