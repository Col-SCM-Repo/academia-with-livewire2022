<?php

namespace App\Http\Livewire\Matricula\Partials;

use App\Repository\DistrictRepository;
use App\Repository\SchoolRepository;
use App\Repository\StudentRepository;
use Livewire\Component;

class Alumno extends Component
{
    public $idEstudiante;
    public $formularioAlumno;

    public $lista_distritos, $lista_ie_procedencia;
    private $distritosRepository, $ie_procedenciaRepository, $estudianteRepository;

    protected $listeners = [
        'ya-cargue' => 'getDatosAutocomplete'
    ];

    protected $rules = [
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
    ];

    public function __construct()
    {
        $this->distritosRepository = new DistrictRepository();
        $this->ie_procedenciaRepository = new SchoolRepository();
        $this->estudianteRepository = new StudentRepository();

        $this->lista_distritos = $this->distritosRepository->listaDistritos();
        $this->lista_ie_procedencia = $this->ie_procedenciaRepository->listarEscuelas();
    }


    public function getDatosAutocomplete()
    {
        $this->emit('data-autocomplete', (object)[
            'lista_distritos' => $this->lista_distritos,
            'lista_ie_procedencia' => $this->lista_ie_procedencia,
            //'lista_paises' => $this->lista_paises
        ]);
    }

    public function initialState()
    {
        $this->reset(["formularioAlumno", "idEstudiante"]);
    }

    public function mount()
    {
        self::initialState();
    }

    public function render()
    {
        return view('livewire.matricula.partials.alumno');
    }

    public function create()
    {
        $this->validate();
        // Reglas de validacion
        // * Evaluar si el alumno ya se encuentra en la base de datos, e impedir que se registre como nuevo alumno
        $data = convertArrayUpperCase($this->formularioAlumno);
        if ($this->estudianteRepository->registrarEstudiante($data)) {

            $this->emit('alert-sucess', (object) ['titulo' => 'Alerta', 'mensaje' => 'El alumno registrado correctamente. ']);
            self::initialState();
        } else
            $this->emit('alert-warning', (object) ['titulo' => 'Error', 'mensaje' => 'Hubo un error al registrar al alumno. ']);

        self::initialState();
    }

    public function update()
    {
        $this->validate();
        $data = convertArrayUpperCase($this->formularioAlumno);
        if ($this->estudianteRepository->actualizarEstudiante($this->idEstudiante, $data)) {
            $this->emit('alert-sucess', (object) ['mensaje' => 'El alumno se actualizo correctamente.']);
            self::initialState();
        } else
            $this->emit('alert-warning', (object) ['mensaje' => 'El alumno no fue encontradp.']);
    }

    public function buscar_interno()
    {
        $this->validateOnly('formularioAlumno.dni');
        $informacionAlumno = $this->estudianteRepository->getInformacionEstudiante($this->formularioAlumno['dni']);

        if ($informacionAlumno) {
            $this->formularioAlumno = [
                'dni' => $informacionAlumno->dni,
                'f_nac' => $informacionAlumno->fechaNacimiento,
                'telefono' => $informacionAlumno->telefono,
                'distrito' => $informacionAlumno->distrito,
                'direccion' => $informacionAlumno->direccion,
                'nombres' => $informacionAlumno->nombre,
                'ap_paterno' => $informacionAlumno->apPaterno,
                'ap_materno' => $informacionAlumno->apMaterno,
                'Ie_procedencia' => $informacionAlumno->ieProcedencia,
                'anio_egreso' => $informacionAlumno->anioGraduacion,
                'sexo' => $informacionAlumno->sexo,
            ];
            $this->idEstudiante = $informacionAlumno->idEstudiante;
            $this->validate();
        } else {
            $this->emit('alert-warning', (object) ['titulo' => 'Alerta', 'mensaje' => 'El alumno no fue encontradp. ']);
        }
    }
}
