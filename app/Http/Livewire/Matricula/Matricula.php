<?php

namespace App\Http\Livewire\Matricula;

use App\Enums\EstadosEntidadEnum;
use App\Repository\EnrollmentRepository;
use Livewire\Component;

class Matricula extends Component
{
    public $relative_id, $student_id;
    public $formularioMatricula;
    public $lista_classrooms, $lista_carreras;
    private $_classroomRepository,  $_careersRepository, $enrollmentRepository;

    protected $rules = [
        'formularioMatricula.tipo_matricula' => 'in:normal,beca,semi-beca',
        'formularioMatricula.classroom_id' => 'required | integer | min:1',
        'formularioMatricula.career_id' => 'required | integer | min:1',
        'formularioMatricula.tipo_pago' => 'in:cash,credit',
        'formularioMatricula.cuotas' => 'integer',
        'formularioMatricula.costo' => 'number | min:0',
        'formularioMatricula.observaciones' => 'string',

        // otros
        'formularioMatricula.costo_matricula' => 'number | min:0',

    ];

    protected $listeners = [
        'apòderado_id' => 'apoderadoEncontrado',
        'apòderado_id' => 'alumnoEncontrado',
    ];

    public function __construct()
    {
        /* $this->_classroomRepository = new (); */
        $this->_careersRepository = new EnrollmentRepository();
        $this->enrollmentRepository = new EnrollmentRepository();
    }

    public function mount()
    {
        self::initialState();
    }

    public function render()
    {
        return view('livewire.matricula.matricula');
    }

    public function initialState()
    {
        $this->reset(['relative_id', 'student_id', 'formularioMatricula']);
    }

    public function create()
    {
        $this->validate();

        sweetAlert($this, 'matricula', EstadosEntidadEnum::CREATED);
    }

    public function update()
    {
        $this->validateOnly('formularioMatricula');
        $this->validateOnly('relative_id');
        $this->validateOnly('student_id');

        sweetAlert($this, 'matricula', EstadosEntidadEnum::UPDATED);
    }

    // Funciones adicionales en segundo plano
    private function apoderadoEncontrado($idApoderado)
    {
        $this->relative_id = $idApoderado;
    }

    private function alumnoEncontrado($idAlumno)
    {
        $this->student_id = $idAlumno;
    }
}
