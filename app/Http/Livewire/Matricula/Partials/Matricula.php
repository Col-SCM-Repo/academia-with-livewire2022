<?php

namespace App\Http\Livewire\Matricula\Partials;

use App\Enums\{EstadosEntidadEnum, FormasPagoEnum, TiposParentescosApoderadoEnum};
use App\Repository\{CareerRepository, ClassroomRepository, EnrollmentRepository};
use Exception;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class Matricula extends Component
{
    // Formulario matricula
    public  $estudianteId, $matriculaId;
    private $_matriculaRepository;

    protected $rules = [
    ];

    protected $listeners = [
        'renderizar-matricula'=>'renderizarMatricula',
        'resetear-matricula' => 'resetearMatricula',
       /*  'student-found' => 'setData',
        'change-prop-enrollment' => 'setData',
        'reset-form-matricula' => 'initialState',
        'pagina-cargada-matricula' => 'enviarDataAutocomplete', */

        'cargar-id-estudiante' => 'cambiarIdEstudiante',
        'cargar-id-matricula' =>  'cambiarIdMatricula',
        'retirar-matricula' => 'retirarAlumno',
        'cuotas-pagos-updated'=>'render'
    ];


    public function __construct()
    {
        $this->_matriculaRepository = new EnrollmentRepository();
    }

   /*  public function mount()
    {
        self::initialState();
    } */

    public function render()
    {
        toastAlert($this, 'CARGANDO RENDER MATRICULAS','warning' );
        $matricula = null;
        if( $this->matriculaId ){
            $matricula = $this->_matriculaRepository::find($this->matriculaId);

            $this->emitTo('matricula.partials.matricula-configuracion-general', 'cargar-id-matricula', $matricula->id);
            $this->emitTo('matricula.partials.matricula-configuracion-pagos', 'cargar-id-matricula', $matricula->id );
            $this->emitTo('matricula.partials.pago', 'pagos-matricula-actualizados', $matricula->id);

        }

        return view('livewire.matricula.partials.matricula', compact('matricula'));
    }

    public function renderizarMatricula($matricula_id = null){
        if($matricula_id)
            $this->matriculaId =  $matricula_id;
        self::render();
    }

    public function retirarAlumno( $matricula_id ){
        try {
            $this->_matriculaRepository->eliminar($matricula_id);
            sweetAlert($this, 'alumno', EstadosEntidadEnum::CREATED);
            $this->reset(['matriculaId']);

            $this->emitTo('matricula.partials.matricula-configuracion-general', 'resetear-matricula-general');
            $this->emitTo('matricula.partials.matricula-configuracion-pagos', 'resetear-matricula-pagos');
            $this->emitTo('matricula.partials.pago', 'resetear-pagos');
        } catch (Exception $ex) {
            toastAlert($this, $ex->getMessage());
        }

    }

    public function cambiarIdEstudiante( int $estudiante_id ){
        if (! Session::has('periodo')) {
            toastAlert($this, 'No se encontro un ciclo vigente');
            return;
        }

        $periodo_id = Session::get('periodo')->id;

        $this->estudianteId = $estudiante_id;
        $this->emitTo('matricula.partials.matricula-configuracion-general', 'matricula-estudiante-id', $estudiante_id);
        /* $this->emitTo('matricula.partials.matricula-configuracion-pagos', 'matricula-estudiante-id', $estudiante_id); */
        $matriculaVigente = $this->_matriculaRepository->buscarMatriculaVigente( $estudiante_id, $periodo_id);
        if($matriculaVigente){
            $this->emitTo('matricula.partials.matricula-configuracion-general', 'cargar-id-matricula', $matriculaVigente->id);
            $this->emitTo('matricula.partials.matricula-configuracion-pagos', 'cargar-id-matricula', $matriculaVigente->id);
            $this->matriculaId = $matriculaVigente->id;
        }
        else{
            $this->emitTo('matricula.partials.pago', 'resetear-pagos');
        }

    }

    public function cambiarIdMatricula( int $matricula_id ){
        $this->matriculaId = $matricula_id;
    }

    public function resetearMatricula( bool $completo = false ){
        $this->reset(['matriculaId']);
        $this->emitTo('matricula.partials.matricula-configuracion-general', 'resetear-matricula-general', $completo);
        $this->emitTo('matricula.partials.matricula-configuracion-pagos', 'resetear-matricula-pagos');
        if($completo){
            $this->reset(['estudianteId']);
        }
    }
}

