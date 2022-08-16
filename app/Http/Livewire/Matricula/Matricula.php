<?php

namespace App\Http\Livewire\Matricula;

use App\Enums\EstadosEntidadEnum;
use App\Enums\TiposParentescosApoderadoEnum;
use App\Repository\ClassroomRepository;
use App\Repository\EnrollmentRepository;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class Matricula extends Component
{
    public $relative_id, $student_id, $matricula_id;

    public $formularioMatricula;

    // variables de la vista
    // numero cuotas 
    // costo matricula


    public $lista_classrooms, $lista_carreras, $vacantes_total, $vacantes_disponible;
    private $_classroomRepository,  $_careersRepository, $_matriculaRepository;

    protected $rules = [
        'formularioMatricula.tipo_matricula' => 'required|in:normal,beca,semi-beca',
        'formularioMatricula.classroom_id' => 'required|integer|min:1',
        'formularioMatricula.career_id' => 'required|integer|min:1',
        'formularioMatricula.tipo_pago' => 'required|in:cash,credit',       // agregar evento
        'formularioMatricula.cuotas' => 'integer|min:0|max:3',                    // hacer dinamico
        'formularioMatricula.costo_matricula' => 'required|numeric|min:0',
        'formularioMatricula.costo' => 'required|numeric|min:0',
        'formularioMatricula.observaciones' => 'string',

        // falta agregar un array de objetos
        'formularioMatricula.listaCuotas' => 'array',

        'relative_id' => 'required|numeric|min:1',
        'student_id' => 'required|numeric|min:1',
    ];

    protected $listeners = [
        'alumno_id' => 'alumnoEncontrado',
        'apòderado_id' => 'apoderadoEncontrado',
    ];

    public function __construct()
    {
        $this->_classroomRepository = new ClassroomRepository();
        //$this->_careersRepository = new CareerRepository();
        $this->_matriculaRepository = new EnrollmentRepository();
    }

    public function mount()
    {
        self::initialState();
    }

    public function updated($name, $value)
    {
        if ($name == 'formularioMatricula.classroom_id') {
            if ($value != null && $value != "") {
                $this->vacantes_total = $this->lista_classrooms[$value]['total_vacantes'];
                $this->vacantes_disponible = $this->lista_classrooms[$value]['vacantes_disponibles'];
                $this->formularioMatricula['costo'] = $this->lista_classrooms[$value]['costo'];
            } else {
                $this->vacantes_total = '';
                $this->vacantes_disponible = '';
                $this->formularioMatricula = '';
            }
        }
    }

    public function render()
    {
        if (Session::has('periodo'))
            $this->lista_classrooms = $this->_classroomRepository->getListaClases(Session::get('periodo')->id);
        else
            $this->lista_classrooms = [];
        return view('livewire.matricula.matricula');
    }

    public function initialState()
    {
        $this->reset(['relative_id', 'student_id', 'formularioMatricula', 'matricula_id']);
        $this->formularioMatricula['cuotas'] = 0;
    }

    public function create()
    {
        $this->validate();

        $formularioMatriculaObj = convertArrayUpperCase($this->formularioMatricula);

        $modelMatricula = $this->_matriculaRepository->builderModelRepository();
        $modelMatricula->tipo_matricula = $formularioMatriculaObj->tipo_matricula;
        $modelMatricula->estudiante_id = $this->student_id;
        $modelMatricula->aula_id = $formularioMatriculaObj->classroom_id;
        $modelMatricula->apoderado_id = $this->relative_id;
        $modelMatricula->relacion_apoderado = TiposParentescosApoderadoEnum::PADRE;
        $modelMatricula->carrera_id = $formularioMatriculaObj->career_id;
        $modelMatricula->tipo_pago = $formularioMatriculaObj->tipo_pago;
        $modelMatricula->cantidad_cuotas = $formularioMatriculaObj->cuotas;
        $modelMatricula->costo_matricula = $formularioMatriculaObj->costo_matricula;
        $modelMatricula->costo_ciclo = $formularioMatriculaObj->costo;
        $modelMatricula->observaciones = $formularioMatriculaObj->observaciones;

        $matriculaCreada = $this->_matriculaRepository->registrarMatricula($modelMatricula);
        if ($matriculaCreada) {
            $this->matricula_id = $matriculaCreada->id;
            $this->emitTo('matricula.pago', 'matricula_id', $matriculaCreada->id);
            sweetAlert($this, 'matricula', EstadosEntidadEnum::CREATED);
        } else
            toastAlert($this, 'Error al registar matricula');
    }

    public function update()
    {
        $this->validateOnly('formularioMatricula');
        $this->validateOnly('relative_id');
        $this->validateOnly('student_id');

        sweetAlert($this, 'matricula', EstadosEntidadEnum::UPDATED);
    }

    // Funciones adicionales en segundo plano
    public function apoderadoEncontrado($idApoderado)
    {
        $this->relative_id = $idApoderado;
    }

    public function alumnoEncontrado($idAlumno)
    {
        $this->student_id = $idAlumno;
    }
}



/*

        <!------------------------------- End: matricula ------------------------------->


        Una Matricula


        Dtos requridos

        // Table matricula
        * code
        * type
        * student_id
        * relative_id
        * classroom_id
        * relative_relationship
        * user_id
        * career_id
        * payment_type
        * fees_quantity
        * period_cost
        * cancelled
        * observations

        <br>
        // Cuotas (payments) al matricularse se indica las cuotas
        * enrollment_id
        * order
        * type
        * amount
        * state

        <br>
        // Pagos (pagos realizados pòr el alumno)
        * installment_id
        * amount
        * type
        * concept_type
        * user_id
        * payment_id
        * serie
        * numeration

        <br>
        para pagos se tiene en cuenta la tabla secuences
        * se utiliza un numero de serie (revisar) *
*/
