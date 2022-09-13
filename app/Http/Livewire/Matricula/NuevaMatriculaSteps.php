<?php

namespace App\Http\Livewire\Matricula;

use Livewire\Component;

class NuevaMatriculaSteps extends Component
{
    protected $listeners = [
        'alumno-id-encontrado' => 'estudianteEncontrado'
    ];

    public function render()
    {
        return view('livewire.matricula.nueva-matricula-steps');
    }

    public function estudianteEncontrado( int $estudiante_id){
        // Enviar evento a apoderado, matricula y pagos
        $this->emitTo('matricula.partials.apoderado', 'cargar-id-estudiante', $estudiante_id);
        $this->emitTo('matricula.partials.matricula', 'cargar-id-estudiante', $estudiante_id);
        /* $this->emitTo('matricula.partials.pagos', 'cargar-id-estudiante', $estudiante_id); */
    }


}
