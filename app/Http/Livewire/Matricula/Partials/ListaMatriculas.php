<?php

namespace App\Http\Livewire\Matricula\Partials;

use App\Repository\EnrollmentRepository;
use Livewire\Component;

class ListaMatriculas extends Component
{
    public $estudianteId, $listaMatriculas;
    private $_matriculaRepository;

    protected $listeners = [
        'cargar-id-estudiante' => 'cambiarIdEstudiante',
    ];

    public function __construct()
    {
        $this->_matriculaRepository = new EnrollmentRepository();
    }

    public function render()
    {
        toastAlert($this, 'CARGANDO RENDER LISTA MATRICULAS','warning' );
        $this->listaMatriculas = $this->estudianteId? $this->_matriculaRepository->listaMatriculasEstudiante($this->estudianteId) :  [];
        return view('livewire.matricula.partials.lista-matriculas');
    }

    public function cambiarIdEstudiante( int $estudiante_id ){
        $this->estudianteId = $estudiante_id;
    }

    public function cargarMatricula( int $matricula_id ){
        $this->emitTo('matricula.partials.matricula','cargar-id-matricula', $matricula_id);
    }

}
