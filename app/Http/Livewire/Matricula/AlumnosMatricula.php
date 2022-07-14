<?php

namespace App\Http\Livewire\Matricula;

use Livewire\Component;

class AlumnosMatricula extends Component
{
    public $search = "";
    public $alumnos = [];



    public function render()
    {
        return view('livewire.matricula.alumnos-matricula');
    }
}
