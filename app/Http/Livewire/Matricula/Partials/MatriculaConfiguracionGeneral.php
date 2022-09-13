<?php

namespace App\Http\Livewire\Matricula\Partials;

use App\Repository\CareerRepository;
use App\Repository\ClassroomRepository;
use App\Repository\EnrollmentRepository;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

// NOTA: Definir becas
class MatriculaConfiguracionGeneral extends Component
{
    // Formulario matricula
    public  $matricula_id;
    public  $classroom, $carrera, $costoCiclo, $observaciones;

    public  $estudianteId;
    public  $listaClassrooms, $listaCarreras, $totalVacantes, $vacantesDisponibles;
    private $_classroomRepository, $_matriculaRepository, $_carrerasRepository;

    protected $listeners = [
        'pagina-cargada-matricula' => 'enviarDataAutocomplete'
    ];

    protected $rules = [
        'estudianteId' => 'required | integer | min:1',

        /* 'tipo_matricula' => 'required | string | in:normal,beca,semi-beca', */
        'matricula_id' => 'nullable | integer | min:1',
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
    }

    public function initialState(){
        $this->reset(['matricula_id', 'classroom', 'carrera', 'costoCiclo', 'observaciones']);
        // Agregar evento para limpiar matricula-pagos
    }

    public function mount(){
        self::initialState();
        $this->listaCarreras =$this->_carrerasRepository->listarCarreras();
    }

    public function render()
    {
        $this->listaClassrooms = Session::has('periodo') ? $this->_classroomRepository->getListaClases(Session::get('periodo')->id) : [];
        $matricula = $this->matricula_id? $this->_matriculaRepository::find($this->matricula_id):null;
        return view('livewire.matricula.partials.matricula-configuracion-general', compact('matricula'));
    }

    /*Funciones CRUD*/
    public function create(){

    }

    public function update(){

    }

    public function delete(){

    }

    /* Funciones Internas  */
    public function updatedClassroom( $classroom_id ){
        if($classroom_id != ''){
            $this->totalVacantes = $this->listaClassrooms[$classroom_id]['total_vacantes'];
            $this->vacantesDisponibles = $this->listaClassrooms[$classroom_id]['vacantes_disponibles'];
            $this->costoCiclo = $this->listaClassrooms[$classroom_id]['costo'];
        }
        else $this->reset([ 'totalVacantes', 'vacantesDisponibles', 'costoCiclo' ]);
    }

    private function buildModeloMatricula(){

    }

    public function enviarDataAutocomplete()
    {
        $this->emit('data-autocomplete-matricula', (object)[ "carreras" => $this->listaCarreras ]);
    }
}
