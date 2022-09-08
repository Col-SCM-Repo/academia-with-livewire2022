<?php

namespace App\Http\Livewire\Matricula;

use App\Repository\StudentRepository;
use Livewire\Component;

class BuscarListaAlumnos extends Component
{
    public $busqueda;
    public $listaEstudiantes, $estudianteSeleccionado;
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
        $this->listaEstudiantes = $this->_estudianteRepository->getListaAlumnos($this->busqueda, 25);
        return view('livewire.matricula.buscar-lista-alumnos');
    }

    public function seleccionarEstudiante( int $estudiante_id ){
        $this->estudianteSeleccionado = $estudiante_id;
        $this->emitTo('matricula.informacion-alumno','cargar-data-informacion-alumno', $estudiante_id);
    }
}
