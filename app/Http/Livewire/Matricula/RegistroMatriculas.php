<?php

namespace App\Http\Livewire\Matricula;

use App\Repository\EnrollmentRepository;
use Livewire\Component;

class RegistroMatriculas extends Component
{
    public $estudianteId, $listaMatriculas, $formularioInformacionMatricula;
    private $_matriculaRepository;

    protected $listeners = [
        'cargar-data-matricula' => 'cargarDataMatricula',
        'enrollment-updated' => 'render'
    ];

    protected $rules = [
        'estudianteId'=>'required | integer | min:1'
    ];

    public function __construct()
    {
        $this->_matriculaRepository = new EnrollmentRepository();
    }

    public function mount (){
        self::initialState();
    }

    public function render()
    {
        if($this->estudianteId){
            $this->listaMatriculas = $this->_matriculaRepository->listaMatriculasEstudiante($this->estudianteId);
        }
        return view('livewire.matricula.registro-matriculas');
    }

    public function initialState(){
        $this->reset('formularioInformacionMatricula');
    }

    /********************** Funciones modal y eventos **********************/

    public function nuevaMatricula(){
        $this->emitTo('matricula.matricula','reset-form-matricula');
        $this->emitTo('matricula.matricula','change-prop-enrollment', (object)['name' => 'student_id', 'value'=>$this->estudianteId] );
        openModal($this, '#form-modal-nueva-matricula');

    }

    public function mostrarInformacionMatricula( int $matricula_id ){
        self::initialState();
        // Consultar informacion
        // abrir modal
        dd($matricula_id);

    }

    public function retirarMatricula( int $matricula_id ){
        self::initialState();
        // Consultar informacion
        // abrir modal
        dd($matricula_id);

    }

    public function cargarDataMatricula( int $estudiante_id ){
        $this->estudianteId = $estudiante_id;
    }

}
