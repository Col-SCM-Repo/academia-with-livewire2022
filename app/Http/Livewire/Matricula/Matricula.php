<?php

namespace App\Http\Livewire\Matricula;

use App\Repository\EnrollmentRepository;
use Livewire\Component;

class Matricula extends Component
{
    public $relative_id, $student_id;
    public $formularioMatricula;
    public $lista_classrooms, $lista_carreras;
    public $_classroomRepository,  $_careersRepository, $enrollmentRepository;

    protected $rules = [
        'relative_id' => 'required | integer',
        'student_id' => 'required | integer',
        'formularioMatricula.tipo_matricula' => 'in:normal,beca,semi-beca',
        'formularioMatricula.classroom_id' => 'required | integer | min:1',
        'formularioMatricula.career_id' => 'required | integer | min:1',
        'formularioMatricula.tipo_pago' => 'in:cash,credit',
        'formularioMatricula.cuotas' => 'integer',
        'formularioMatricula.costo' => 'integer',
        'formularioMatricula.observaciones' => 'string',
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
}
