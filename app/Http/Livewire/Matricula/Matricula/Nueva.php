<?php

namespace App\Http\Livewire\Matricula\Matricula;

use App\Repository\ClassroomRepository;
use App\Repository\DistrictRepository;
use App\Repository\EnrollmentRepository;
use App\Repository\LevelRepository;
use App\Repository\OccupationRepository;
use App\Repository\PaymentRepository;
use App\Repository\RelativeRepository;
use App\Repository\SchoolRepository;
use App\Repository\StudentRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class Nueva extends Component
{
    public  $formularioAlumno, $formularioApoderado, $formularioMatricula;

    public  $alumno_id,
        $apoderado_id,  // relative_ID
        $matricula_id;

    // data del repository
    public  $listaDistritos,
        $listaInstitucionesEducativas,
        $listaOcupaciones,
        $listaClases;

    // Repositorios
    private $_distritoRepository,
        $_escuelasRepository,
        $_ocupacionRepository,
        $_clasesRepository,
        $_nivelRepository,
        $_estudianteRepository,
        $_apoderadoRepository,
        $_matriculaRepository,
        $_pagoRepository;

    protected $rules = [
        // Formulario alumno
        'formularioAlumno.dni' => "required | integer | min:8",
        'formularioAlumno.f_nac' => "required | date_format:Y-m-d",
        'formularioAlumno.telefono' => "required | string | min: 4",
        'formularioAlumno.distrito' => "required | string ",
        'formularioAlumno.direccion' => "required | string | min: 4",
        'formularioAlumno.nombres' => "required | string | min: 4",
        'formularioAlumno.ap_paterno' => "required | string | min: 4",
        'formularioAlumno.ap_materno' => "required | string | min: 4",
        'formularioAlumno.Ie_procedencia' => "required | string | min: 4",
        'formularioAlumno.anio_egreso' => "required | date_format:Y",
        'formularioAlumno.sexo' => "required | string | min:4 | max:8",

        // Formulario apoderado
        'formularioApoderado.dni' => "required | integer | min:8",
        'formularioApoderado.f_nac' => "required | date_format:Y-m-d",
        'formularioApoderado.telefono' => "required | string | min: 4",
        'formularioApoderado.distrito' => "required | string ",
        'formularioApoderado.direccion' => "required | string | min: 4",
        'formularioApoderado.nombres' => "required | string | min: 4",
        'formularioApoderado.ap_paterno' => "required | string | min: 4",
        'formularioApoderado.ap_materno' => "required | string | min: 4",
        'formularioApoderado.ocupacion' => "required | string | min: 4",
        'formularioApoderado.sexo' => "required | string | min:4 | max:8",
        'formularioApoderado.estado_marital' => "required | string | min:4",

        // Formulario matriculas
        'formularioMatricula.tipo_matricula' => 'in:normal,beca,semi-beca',
        'formularioMatricula.classroom_id' => 'required | integer | min:1',
        'formularioMatricula.career_id' => 'required | integer | min:1',
        'formularioMatricula.tipo_pago' => 'in:cash,credit',
        'formularioMatricula.cuotas' => 'integer',
        'formularioMatricula.costo' => 'integer',
        'formularioMatricula.observaciones' => 'string',

        // Identificadores
        'alumno_id' => 'required|integer|min:1',
        'apoderado_id' => 'required|integer|min:1',
        'matricula_id' => 'required|integer|min:1',
    ];

    public function initialState()
    {
        $this->reset([
            'formularioAlumno',
            'formularioApoderado',
            'formularioMatricula',
            'alumno_id',
            'apoderado_id',
            'matricula_id',
        ]);
    }

    public function mount()
    {
        if (Session::has('periodo')) {
            $this->_distritoRepository = new DistrictRepository();
            $this->_escuelasRepository = new SchoolRepository();
            $this->_ocupacionRepository = new OccupationRepository();
            $this->_clasesRepository = new ClassroomRepository();
            $this->_nivelRepository = new LevelRepository();
            $this->_estudianteRepository = new StudentRepository();
            $this->_apoderadoRepository = new RelativeRepository();
            $this->_matriculaRepository = new EnrollmentRepository();
            $this->_pagoRepository = new PaymentRepository();

            $this->listaDistritos = $this->_distritoRepository->listaDistritos();
            $this->listaInstitucionesEducativas = $this->_escuelasRepository->listarEscuelas();
            $this->listaOcupaciones =  $this->_ocupacionRepository::all();
            $this->listaClases =  $this->_nivelRepository->buscarAulasPorNivel(Session::get('periodo')->id);
        } else {
            $this->listaDistritos = [];
            $this->listaInstitucionesEducativas = [];
            $this->listaOcupaciones = [];
            $this->listaClases = [];
        }
    }

    public function render()
    {
        return view('livewire.matricula.matricula.nueva');
    }

    // CRUD alumno
    public function createAlumno()
    {
        Log::debug($this->formularioAlumno);
        $this->validate();
        $this->emit('wizzard-step', 'next');
    }
    public function updateAlumno()
    {
    }
    public function searchAlumnoInterno()
    {
    }
    public function searchAlumnoExterno()
    {
    }

    // CRUD alumno
    public function createApoderado()
    {
    }
    public function updateApoderado()
    {
    }
    public function searchApoderadoInterno()
    {
    }
    public function searchApoderadoExterno()
    {
    }

    // CRUD alumno
    public function createMatricula()
    {
    }
    public function updateMatricula()
    {
    }
}
