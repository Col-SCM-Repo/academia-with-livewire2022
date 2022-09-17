<?php

namespace App\Http\Livewire\Matricula\Partials;

use App\Enums\EstadosEntidadEnum;
use App\Repository\CareerRepository;
use App\Repository\ClassroomRepository;
use App\Repository\EnrollmentRepository;
use App\Repository\InstallmentRepository;
use App\Repository\ScholarshipRepository;
use Exception;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

// NOTA: Definir becas
class MatriculaConfiguracionGeneral extends Component
{
    // Formulario matricula
    public  $matriculaId;
    public  $descuento, $estudianteId, $classroom, $carrera, $costoCiclo, $costoCicloFinal, $observaciones;

    public  $listaClassrooms, $listaCarreras, $listaDescuentos, $totalVacantes, $vacantesDisponibles;
    private $_classroomRepository, $_matriculaRepository, $_carrerasRepository, $_descuentosRepository, $_cuotasRepository;

    protected $listeners = [
        'pagina-cargada-matricula' => 'enviarDataAutocomplete',
        'matricula-estudiante-id' => 'cargarIdEstudiante',
        'cargar-id-matricula' => 'cargarIdMatricula',
        'descuentos-actualizados' => 'render',

        // Eventos de matricula-configuracion-pagos
        'cuota-generada'=> 'cuotaGenerada',

    ];

    protected $rules = [
        'estudianteId' => 'required | integer | min:1',

        /* 'tipo_matricula' => 'required | string | in:normal,beca,semi-beca', */
        'matriculaId' => 'nullable | integer | min:1',
        'descuento' => 'nullable | integer | min:1',
        'classroom' => 'required | integer | min:1',
        'carrera' => 'required|string | min:3',
        'costoCiclo' => 'required | numeric | min:0',
        'observaciones' => ' nullable | string '
    ];

    public function __construct()
    {
        $this->_classroomRepository = new ClassroomRepository();
        $this->_carrerasRepository = new CareerRepository();
        $this->_matriculaRepository = new EnrollmentRepository();
        $this->_descuentosRepository = new ScholarshipRepository();
        $this->_cuotasRepository = new InstallmentRepository();
    }

    public function initialState(){
        $this->reset(['matriculaId', 'classroom', 'carrera', 'costoCiclo', 'observaciones']);
        // Agregar evento para limpiar matricula-pagos
    }

    public function mount(){
        self::initialState();
        toastAlert($this, 'asdasda');
        $this->listaCarreras =$this->_carrerasRepository->listarCarreras();
        $this->emit('data-autocomplete-matricula', (object)[ "carreras" => $this->listaCarreras ]);
    }

    public function render()
    {
        $this->emit('render-matriculageneral');
        toastAlert($this, 'Render GENERAL');
        $this->listaDescuentos = $this->_descuentosRepository->listaDescuentos();
        $this->listaClassrooms = Session::has('periodo') ? $this->_classroomRepository->getListaClases(Session::get('periodo')->id) : [];
        /* $matricula = $this->matriculaId? $this->_matriculaRepository::find($this->matriculaId):null;
        if($matricula) self::vincularInformacionMatricula($matricula); */
        return view('livewire.matricula.partials.matricula-configuracion-general');
    }

    /*Funciones CRUD*/
    public function create(){
        $this->validate();
        $modeloMatricula = self::buildModeloMatricula();
        /* dd($modeloMatricula); */
        try {
            $matriculaCreada = $this->_matriculaRepository->registrar($modeloMatricula);
            $this->emitUp('cargar-id-matricula', $matriculaCreada->id);
            $this->emitTo('matricula.partials.matricula-configuracion-pagos', 'cargar-id-matricula', $matriculaCreada->id);
            $this->matriculaId = $matriculaCreada->id;
            sweetAlert($this, 'matricula', EstadosEntidadEnum::CREATED);

        } catch (Exception $err) {
            toastAlert($this, $err->getMessage());
        }
    }

    public function update(){
        $this->validate();
        $modeloMatricula = self::buildModeloMatricula();

        // Validar eliminacion automatica de pagos en el caso que cambie el monto a pagar
        // En caso de no haber cambio no cambiar el estado de matiucla a pendiende de actualizacion

        try {
            $this->_matriculaRepository->actualizar($this->matriculaId, $modeloMatricula);
            $this->_cuotasRepository->evaluarRequisitosActualizacion($this->matriculaId);
            /* $this->emitUp('cargar-id-matricula', $matriculaCreada->id); */
            /* $this->emitTo('matricula.partials.matricula-configuracion-pagos', 'cargar-id-matricula', $matriculaCreada->id); */
            sweetAlert($this, 'matricula', EstadosEntidadEnum::UPDATED);
        } catch (Exception $err) {
            toastAlert($this, $err->getMessage());
        }
    }

    public function delete(){

    }

    /* Funciones Internas  */
    public function updatedClassroom( $classroom_id ){
        if($classroom_id != ''){
            $this->totalVacantes = $this->listaClassrooms[$classroom_id]['total_vacantes'];
            $this->vacantesDisponibles = $this->listaClassrooms[$classroom_id]['vacantes_disponibles'];
            $this->costoCiclo = $this->listaClassrooms[$classroom_id]['costo'];
            $this->costoCicloFinal = $this->listaClassrooms[$classroom_id]['costo'];
        }
        else $this->reset([ 'totalVacantes', 'vacantesDisponibles', 'costoCiclo', 'costoCicloFinal' ]);
    }

    public function updatedDescuento( $descuento_id ){
        $this->costoCicloFinal = ($descuento_id != '')? $this->_descuentosRepository->calcularDescuento($descuento_id, $this->costoCiclo):$this->costoCiclo;
    }

    private function buildModeloMatricula(){
        $modeloMatricula = $this->_matriculaRepository->builderModelRepository();
        $modeloMatricula->descuento_id = $this->descuento==''? null : $this->descuento;
        $modeloMatricula->estudiante_id = $this->estudianteId;
        $modeloMatricula->aula_id = $this->classroom;
        $modeloMatricula->carrera = $this->carrera;
        $modeloMatricula->costo_ciclo = $this->costoCiclo;
        $modeloMatricula->costo_ciclo_final = $this->costoCicloFinal;
        $modeloMatricula->observaciones = $this->observaciones;
        return $modeloMatricula;
    }

    private function vincularInformacionMatricula( object $matricula){
        $this->descuento = $matricula->scholarship_id;
        /* $this->estudianteId = $matricula->student_id; */
        $this->classroom = $matricula->classroom_id;
        $this->carrera = $matricula->career->career;
        $this->costoCiclo = $matricula->period_cost;
        $this->costoCicloFinal = $matricula->period_cost_final;
        $this->observaciones = $matricula->observations;
    }

    public function enviarDataAutocomplete()
    {
        $this->emit('data-autocomplete-matricula', (object)[ "carreras" => $this->listaCarreras ]);
    }

    public function cargarIdEstudiante(int $estudiante_id){
        $this->estudianteId=$estudiante_id;
    }

    public function cargarIdMatricula(int $matricula_id){
        $this->matriculaId=$matricula_id;
        $matricula = $this->_matriculaRepository::find($matricula_id);
        if($matricula) self::vincularInformacionMatricula($matricula);
        else toastAlert($this, 'Error al cargar la informacion de la matricula');
    }

    // Listeners de matricula-configuracion-pagos
    public function cuotaGenerada( ){
        $this->emitUp('cuotas-pagos-updated');
    }

}
