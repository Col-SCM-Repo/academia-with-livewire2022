<?php

namespace App\Http\Livewire\Matricula;

use App\Repository\StudentRepository;
use Livewire\Component;

class BuscarListaAlumnos extends Component
{
    public $busqueda;

    private $_estudianteRepository;

    public function __construct()
    {
        $this->_estudianteRepository = new StudentRepository();
    }

    protected $rules=[
        'busqueda' => 'required | string | max: 30',
    ];

    public function initialState(){
        $this->busqueda="";
    }

    public function mount(){
        self::initialState();
    }

    public function render()
    {
        $listaAlumnos = $this->_estudianteRepository->getListaAlumnos($this->busqueda, 25);
        return view('livewire.matricula.buscar-lista-alumnos')->with('alumnos', $listaAlumnos);
    }
}
