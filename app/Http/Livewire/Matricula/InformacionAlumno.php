<?php

namespace App\Http\Livewire\Matricula;

use App\Enums\EstadosAlertasEnum;
use App\Repository\StudentRepository;
use Livewire\Component;

class InformacionAlumno extends Component
{
    private $_estudianteRepository;

    public function __construct()
    {
        $this->_estudianteRepository = new StudentRepository();
    }

    protected $listeners = [
        'cargar-data-informacion-alumno' => 'cargarDataAlumno'
    ];

    public function render()
    {
        return view('livewire.matricula.informacion-alumno');
    }

    public function cargarDataAlumno(int $estudiante_id){
        $estudiante = $this->_estudianteRepository::find($estudiante_id);

        if($estudiante){
            $this->emitTo('matricula.alumno', 'cargar-data-alumno', $estudiante->entity->document_number);
            $this->emitTo('matricula.apoderado', 'cargar-data-apoderado', $estudiante_id);
            $this->emitTo('matricula.registro-matriculas', 'cargar-data-matricula', $estudiante_id);
            $this->emitTo('matricula.pago', 'cargar-data-pagos', $estudiante_id);
        }
        else toastAlert($this, 'Error al cargar datos del estudiante.', EstadosAlertasEnum::WARNING);
    }
}
