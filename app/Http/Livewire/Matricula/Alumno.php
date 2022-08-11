<?php

namespace App\Http\Livewire\Matricula;

use App\Enums\EstadosAlertasEnum;
use App\Enums\EstadosEntidadEnum;
use App\Repository\DistrictRepository;
use App\Repository\SchoolRepository;
use App\Repository\StudentRepository;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Alumno extends Component
{
    // checar eso del ID ESTUDIANTE *WARNING*
    public $idEstudiante;
    public $formularioAlumno;

    public $lista_distritos, $lista_ie_procedencia;

    private $distritosRepository, $ie_procedenciaRepository, $_estudianteRepository;
    private $componenteExterno;

    protected $listeners = [
        //    'reset-form-alumno' => 'initialState',
        'pagina-cargada-getdata' => 'enviarDataAutocomplete',
    ];

    public function enviarDataAutocomplete()
    {
        $this->emit('data-autocomplete', (object)[
            "distritos" => $this->lista_distritos,
            "instituciones" => $this->lista_ie_procedencia,
        ]);
    }

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

        'formularioAlumno.cuotas' => "required | string | min:0 | max:8",
    ];

    public function __construct()
    {
        $this->distritosRepository = new DistrictRepository();
        $this->ie_procedenciaRepository = new SchoolRepository();
        $this->_estudianteRepository = new StudentRepository();
    }

    public function mount($ambito = 0) // 1 = externo  <> 0 = componente interno
    {
        self::initialState();

        $this->componenteExterno = $ambito == 1;
        $this->lista_distritos = $this->distritosRepository->listaDistritos();
        $this->lista_ie_procedencia = $this->ie_procedenciaRepository->listarEscuelas();
    }

    public function render()
    {
        return view('livewire.matricula.alumno');
    }

    public function initialState()
    {
        $this->reset(["formularioAlumno", "idEstudiante"]);
    }

    // CRUD alumno
    public function create()
    {
        $this->validate();
        // Reglas de validacion
        // * Evaluar si el alumno ya se encuentra en la base de datos, e impedir que se registre como nuevo alumno
        $estudianteCreado = $this->_estudianteRepository->registrarEstudiante(convertArrayUpperCase($this->formularioAlumno));
        if ($estudianteCreado) {
            $this->idEstudiante = $estudianteCreado->id;
            sweetAlert($this, 'alumno', EstadosEntidadEnum::CREATED);
            if ($this->componenteExterno) {
                openModal($this, 'form-modal-alumno', false);
                self::initialState();
            } else {
                // siguiente paso  o habilitar boton
                $this->emit('alumno_id', $estudianteCreado->id);
            }
        } else
            toastAlert($this, 'Ocurrio un error al registrar alumno');
    }

    public function update()
    {
        $this->validate();
        if ($this->_estudianteRepository->actualizarEstudiante($this->idEstudiante, convertArrayUpperCase($this->formularioAlumno))) {
            if ($this->componenteExterno) {
                openModal($this, 'form-modal-alumno', false);
                self::initialState();
                // cerrar modal y limpiar formulario
            } /* else {
                //  siguiente paso  o habilitar boton
                //  $this->emit('wizzard-step', 'next');
            } */

            sweetAlert($this, 'alumno', EstadosEntidadEnum::UPDATED);
        } else
            toastAlert($this, 'Ocurrio un error al actualizar alumno');
    }

    public function buscar_interno()
    {
        $this->validateOnly('formularioAlumno.dni');
        $informacionAlumno = $this->_estudianteRepository->getInformacionEstudiante($this->formularioAlumno['dni']);

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
            $this->emit('alumno_id', $informacionAlumno->idEstudiante);
            $this->validate();
        } else {
            toastAlert($this, 'El alumno no pudo ser encontrado', EstadosAlertasEnum::WARNING);
            self::initialState();
        }
    }

    public function buscar_externo()
    {
    }
}
