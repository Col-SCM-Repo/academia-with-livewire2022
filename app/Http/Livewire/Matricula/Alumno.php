<?php

namespace App\Http\Livewire\Matricula;

use App\Enums\EstadosAlertasEnum;
use App\Enums\EstadosEntidadEnum;
use App\Repository\DistrictRepository;
use App\Repository\SchoolRepository;
use App\Repository\StudentRepository;
use Exception;
use Livewire\Component;

class Alumno extends Component
{
    // checar eso del ID ESTUDIANTE *WARNING*
    public $idEstudiante, $formularioAlumno;

    public $lista_distritos, $lista_ie_procedencia;
    private $distritosRepository, $ie_procedenciaRepository, $_estudianteRepository;

    protected $listeners = [
        'reset-form-alumno' => 'initialState',
        'pagina-cargada-alumno' => 'enviarDataAutocomplete',
        'change-props-alumno' => 'setData',
        'cargar-data-alumno' => 'cargarDataAlumno',
    ];

    protected $rules = [
        'formularioAlumno.dni' => "required | string | min:8",
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
        $this->_estudianteRepository = new StudentRepository();
    }

    public function mount()
    {
        self::initialState();
        $this->lista_distritos = $this->distritosRepository->listaDistritos();
        $this->lista_ie_procedencia = $this->ie_procedenciaRepository->listarEscuelas();
        $this->formularioAlumno = [ 'dni' => null, 'f_nac' => null, 'telefono' => null, 'distrito' => null, 'direccion' => null, 'nombres' => null,
                                    'ap_paterno' => null, 'ap_materno' => null, 'Ie_procedencia' => null, 'anio_egreso' => null, 'sexo' => null ];
    }

    public function render()
    {
        return view('livewire.matricula.alumno');
    }

    public function initialState()
    {
        $this->reset(["formularioAlumno", "idEstudiante"]);
    }

    /***********************************************************  CRUD *************************************************************/
    public function create()
    {
        $this->validate();
        $moEstudiante = $this->_estudianteRepository->builderModelRepository();
        $moEstudiante->apellido_paterno = formatInputStr( $this->formularioAlumno['ap_paterno'] ) ;
        $moEstudiante->apellido_materno = formatInputStr( $this->formularioAlumno['ap_materno'] );
        $moEstudiante->nombres = formatInputStr( $this->formularioAlumno['nombres'] );
        $moEstudiante->direccion = formatInputStr( $this->formularioAlumno['direccion'] );
        $moEstudiante->distrito = formatInputStr( $this->formularioAlumno['distrito'] );
        $moEstudiante->telefono = formatInputStr( $this->formularioAlumno['telefono'] );
        // $moEstudiante->celular = '';
        $moEstudiante->fecha_nacimiento = formatInputStr( $this->formularioAlumno['f_nac'] );
        $moEstudiante->sexo = formatInputStr( $this->formularioAlumno['sexo'] );
        $moEstudiante->dni = formatInputStr( $this->formularioAlumno['dni'] );
        $moEstudiante->nombre_escuela = formatInputStr( $this->formularioAlumno['Ie_procedencia'] );
        $moEstudiante->anio_graduacion = formatInputStr( $this->formularioAlumno['anio_egreso'] );
        // $moEstudiante->nombre_archivo_foto = '';

        try {
            $estudianteCreado = $this->_estudianteRepository->registrar($moEstudiante);
            $this->idEstudiante = $estudianteCreado->id;
            $this->emit('cargar-data-apoderado', $estudianteCreado->id);
            sweetAlert($this, 'alumno', EstadosEntidadEnum::CREATED);
        } catch (Exception $ex) {
            toastAlert($this, $ex->getMessage());
        }
    }

    public function update()
    {
        $this->validate();
        $moEstudiante = $this->_estudianteRepository->builderModelRepository();
        $moEstudiante->apellido_paterno = formatInputStr( $this->formularioAlumno['ap_paterno'] ) ;
        $moEstudiante->apellido_materno = formatInputStr( $this->formularioAlumno['ap_materno'] );
        $moEstudiante->nombres = formatInputStr( $this->formularioAlumno['nombres'] );
        $moEstudiante->direccion = formatInputStr( $this->formularioAlumno['direccion'] );
        $moEstudiante->distrito = formatInputStr( $this->formularioAlumno['distrito'] );
        $moEstudiante->telefono = formatInputStr( $this->formularioAlumno['telefono'] );
        // $moEstudiante->celular = '';
        $moEstudiante->fecha_nacimiento = formatInputStr( $this->formularioAlumno['f_nac'] );
        $moEstudiante->sexo = formatInputStr( $this->formularioAlumno['sexo'] );
        $moEstudiante->dni = formatInputStr( $this->formularioAlumno['dni'] );
        $moEstudiante->nombre_escuela = formatInputStr( $this->formularioAlumno['Ie_procedencia'] );
        $moEstudiante->anio_graduacion = formatInputStr( $this->formularioAlumno['anio_egreso'] );
        // $moEstudiante->nombre_archivo_foto = '';

        try {
            $this->_estudianteRepository->actualizar($this->idEstudiante, $moEstudiante);
            sweetAlert($this, 'alumno', EstadosEntidadEnum::UPDATED);
        } catch (Exception $ex) {
            toastAlert($this, $ex->getMessage() );
        }
    }

    /***********************************************************  Funciones listeners *************************************************************/
    public function buscar_interno()
    {
        $this->validateOnly('formularioAlumno.dni');
        $dni = $this->formularioAlumno['dni'];
        $informacionAlumno = $this->_estudianteRepository->getInformacionEstudiante($dni);

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
            $this->emit('cargar-data-apoderado', $informacionAlumno->idEstudiante);
            $this->validate();
        } else {
            toastAlert($this, 'El alumno no pudo ser encontrado', EstadosAlertasEnum::WARNING);
            self::initialState();
            $this->formularioAlumno['dni'] = $dni;
        }
    }

    public function buscar_externo()
    {
    }

    public function cargarDataAlumno( string $dniEstudiante ){
        if( is_string($dniEstudiante) && strlen($dniEstudiante)>=8 ){
            $informacionAlumno = $this->_estudianteRepository->getInformacionEstudiante($dniEstudiante);
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
            } else  toastAlert($this, "No se pudo encontrar informacion del alumno con dni $dniEstudiante", EstadosAlertasEnum::WARNING);
        }
        else toastAlert($this, "Dni $dniEstudiante con formato incorrecto", EstadosAlertasEnum::WARNING);
    }

    public function setData( $data )
    {
        $this->formularioAlumno[$data['name']] = $data['value'];
    }

    public function enviarDataAutocomplete()
    {
        $this->emit('data-autocomplete-alumno', (object)[
            "distritos" => $this->lista_distritos,
            "instituciones" => $this->lista_ie_procedencia,
        ]);
    }

    /***********************************************************  Funciones internas *************************************************************/

}
