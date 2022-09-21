<?php

namespace App\Http\Livewire\Matricula;

use App\Enums\EstadosAlertasEnum;
use App\Repository\StudentRepository;
use Livewire\Component;

class InformacionAlumnoSteps extends Component
{

    private $_estudianteRepository;

    public function __construct()
    {
        $this->_estudianteRepository = new StudentRepository();
    }

    protected $listeners = [
        'estudiante-seleccionado-id' => 'cargarDataAlumno'
    ];

    public function render()
    {
        return view('livewire.matricula.informacion-alumno-steps');
    }

    public function cargarDataAlumno(int $estudiante_id){
        $estudiante = $this->_estudianteRepository::find($estudiante_id);

        if($estudiante){
            $this->emitTo('matricula.partials.alumno', 'cargar-data-alumno', $estudiante->entity->document_number);
            $this->emitTo('matricula.partials.apoderado', 'cargar-id-estudiante', $estudiante_id);
            $this->emitTo('matricula.partials.lista-matriculas', 'cargar-id-estudiante', $estudiante_id);
            $this->emitTo('matricula.partials.matricula', 'cargar-id-estudiante', $estudiante_id);

        }
        else toastAlert($this, 'Error al cargar datos del estudiante.', EstadosAlertasEnum::WARNING);
    }
}
