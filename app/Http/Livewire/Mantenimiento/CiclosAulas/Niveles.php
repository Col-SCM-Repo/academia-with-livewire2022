<?php

namespace App\Http\Livewire\Mantenimiento\CiclosAulas;

use Livewire\Component;

class Niveles extends Component
{
    public $lista_niveles;

    public function __construct()
    {
        
    }

    public function render()
    {
        return view('livewire.mantenimiento.ciclos-aulas.niveles');
    }
}
