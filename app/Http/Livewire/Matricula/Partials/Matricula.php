<?php

namespace App\Http\Livewire\Matricula\Partials;

use App\Enums\{EstadosEntidadEnum, FormasPagoEnum, TiposParentescosApoderadoEnum};
use App\Repository\{CareerRepository, ClassroomRepository, EnrollmentRepository};
use Livewire\Component;

class Matricula extends Component
{
    // Formulario matricula
    public  $estudianteId, $matriculaId;

    protected $rules = [
    ];

    protected $listeners = [
       /*  'student-found' => 'setData',
        'change-prop-enrollment' => 'setData',
        'reset-form-matricula' => 'initialState',
        'pagina-cargada-matricula' => 'enviarDataAutocomplete', */

        'cargar-id-estudiante' => 'cambiarIdEstudiante',
        'cargar-id-matricula' =>  'cambiarIdMatricula',
    ];


    public function __construct()
    {
    }

    public function initialState()
    {

    }

    public function mount()
    {
        self::initialState();
    }

    public function render()
    {
        return view('livewire.matricula.partials.matricula');
    }

    public function cambiarIdEstudiante( int $estudiante_id ){
        $this->estudianteId = $estudiante_id;
        $this->emitTo('matricula.partials.matricula-configuracion-general', 'matricula-estudiante-id', $estudiante_id);
        /* $this->emitTo('matricula.partials.matricula-configuracion-pagos', 'matricula-estudiante-id', $estudiante_id); */
    }

    public function cambiarIdMatricula( int $matricula_id ){
        $this->matriculaId = $matricula_id;
    }
}

