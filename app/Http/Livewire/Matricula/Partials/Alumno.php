<?php

namespace App\Http\Livewire\Matricula\Partials;

use App\Repository\CountryRepository;
use App\Repository\DistrictRepository;
use App\Repository\EntityRepository;
use App\Repository\SchoolRepository;
use App\Repository\StudentRepository;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Alumno extends Component
{
    public $idEstudiante;
    public $formularioAlumno;

    public $lista_distritos, $lista_ie_procedencia;
    private $distritosRepository, $ie_procedenciaRepository,  $entityRepository, $estudianteRepository;

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
        $this->entityRepository = new EntityRepository();
        $this->estudianteRepository = new StudentRepository();
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
        $this->reset(["formularioAlumno"]);

        $this->lista_distritos = $this->distritosRepository->listaDistritos();
        $this->lista_ie_procedencia = $this->ie_procedenciaRepository->listarEscuelas();
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
        Log::debug((array)$this->formularioAlumno);
        $entidad = $this->entityRepository->registrarEntidad((object) $this->formularioAlumno);
        $estudiante = $this->estudianteRepository->registrarEstudiante((object) $this->formularioAlumno, $entidad);

        if ($estudiante)
            $this->emit('alert-sucess', (object) ['titulo' => 'Alerta', 'mensaje' => 'El alumno registrado correctamente. ']);
        else
            $this->emit('alert-warning', (object) ['titulo' => 'Alerta', 'mensaje' => 'El alumno no fue encontradp. ']);

        $this->reset(["formularioAlumno", "idEstudiante"]);
    }

    public function update()
    {
        $this->validate();
        $seActualizo = $this->estudianteRepository->actualizarEstudiante((object) $this->formularioAlumno, $this->idEstudiante);

        if ($seActualizo)
            $this->emit('alert-sucess', (object) ['titulo' => 'Alerta', 'mensaje' => 'El alumno se actualizo correctamente. ']);
        else
            $this->emit('alert-warning', (object) ['titulo' => 'Alerta', 'mensaje' => 'El alumno no fue encontradp. ']);
    }

    public function buscar_interno()
    {
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
        } else {
            $this->emit('alert-warning', (object) ['titulo' => 'Alerta', 'mensaje' => 'El alumno no fue encontradp. ']);
        }
    }

    public function buscar_reniec()
    {
        // -- PENDIENTE --
    }
}
