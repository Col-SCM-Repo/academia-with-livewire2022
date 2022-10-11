<?php

namespace App\Http\Livewire\Aula;

use App\Repository\ClassroomRepository;
use Livewire\Component;

class InformacionListaAlumnos extends Component
{
    public $aula_id;
    public $search, $listaAlumnos;

    public $_aulaRepository;

    public function initialState () {
        $this->reset([ 'search', 'listaAlumnos'  ]);
    }

    public function __construct()
    {
        $this->_aulaRepository = new ClassroomRepository();
    }

    public function mount( $aula_id = null ){
        $this->aula_id = $aula_id;
    }

    public function render()
    {
        $this->listaAlumnos = $this->_aulaRepository->listaEstudiantes($this->aula_id, $this->search);
        return view('livewire.aula.informacion-lista-alumnos');
    }
}
