<?php

namespace App\Http\Livewire\Matricula\Partials;

use App\Enums\EstadosAlertasEnum;
use App\Enums\EstadosEntidadEnum;
use App\Repository\DistrictRepository;
use App\Repository\SchoolRepository;
use App\Repository\StudentRepository;
use Exception;
use Livewire\Component;

class Alumno extends Component
{
    // Formulario alumno
    public  $idEstudiante;
    public  $fecha_nacimiento, $dni, $distrito, $telefono, $nombres, $direccion, $apellido_materno,
            $apellido_paterno, $anio_egreso, $Ie_procedencia, $sexo;

    public  $lista_distritos, $lista_ie_procedencia;
    private $distritosRepository, $ie_procedenciaRepository, $_estudianteRepository;

    protected $listeners = [
        'reset-form-alumno' => 'initialState',
        'pagina-cargada-alumno' => 'enviarDataAutocomplete',
        'cargar-data-alumno' => 'cargarDataAlumno',
    ];

    protected $rules = [
        'dni' => "required | string | min:8",
        'fecha_nacimiento' => "required | date_format:Y-m-d",
        'telefono' => "required | string | min: 4",
        'distrito' => "required | string ",
        'direccion' => "required | string | min: 4",
        'nombres' => "required | string | min: 4",
        'apellido_paterno' => "required | string | min: 4",
        'apellido_materno' => "required | string | min: 4",
        'Ie_procedencia' => "required | string | min: 4",
        'anio_egreso' => "required | date_format:Y",
        'sexo' => "required | string | min:4 | max:8",
    ];

    public function __construct(){
        $this->distritosRepository = new DistrictRepository();
        $this->ie_procedenciaRepository = new SchoolRepository();
        $this->_estudianteRepository = new StudentRepository();
    }

    public function mount(){
        self::initialState();
        $this->lista_distritos = $this->distritosRepository->listaDistritos();
        $this->lista_ie_procedencia = $this->ie_procedenciaRepository->listarEscuelas();
    }

    public function render(){
        return view('livewire.matricula.partials.alumno');
    }

    public function initialState(){
        $this->reset([  "idEstudiante", "dni", "fecha_nacimiento", "telefono", "distrito", "direccion",
                        "nombres", "apellido_paterno", "apellido_materno", "Ie_procedencia", "anio_egreso", "sexo" ]);
    }

    /***********************************************************  CRUD *************************************************************/
    public function create(){
        $this->validate();
        $moEstudiante = $this->_estudianteRepository->builderModelRepository();
        $moEstudiante->apellido_paterno = strtoupper($this->apellido_paterno);
        $moEstudiante->apellido_materno = strtoupper($this->apellido_materno);
        $moEstudiante->nombres = strtoupper( $this->nombres );
        $moEstudiante->direccion = strtoupper( $this->direccion );
        $moEstudiante->distrito = strtoupper( $this->distrito );
        $moEstudiante->telefono = $this->telefono;
        // $moEstudiante->celular = '';
        $moEstudiante->fecha_nacimiento = $this->fecha_nacimiento;
        $moEstudiante->sexo = strtoupper( $this->sexo );
        $moEstudiante->dni = $this->dni;
        $moEstudiante->nombre_escuela = strtoupper( $this->Ie_procedencia );
        $moEstudiante->anio_graduacion = $this->anio_egreso;
        // $moEstudiante->nombre_archivo_foto = '';

        try {
            $estudianteCreado = $this->_estudianteRepository->registrar($moEstudiante);
            $this->idEstudiante = $estudianteCreado->id;
            $this->emit('alumno-id-encontrado', $estudianteCreado->id);
            sweetAlert($this, 'alumno', EstadosEntidadEnum::CREATED);
        } catch (Exception $ex) {
            toastAlert($this, $ex->getMessage());
        }
    }

    public function update(){
        $this->validate();
        $moEstudiante = $this->_estudianteRepository->builderModelRepository();
        $moEstudiante->apellido_paterno = strtoupper($this->apellido_paterno);
        $moEstudiante->apellido_materno = strtoupper($this->apellido_materno);
        $moEstudiante->nombres = strtoupper( $this->nombres );
        $moEstudiante->direccion = strtoupper( $this->direccion );
        $moEstudiante->distrito = strtoupper( $this->distrito );
        $moEstudiante->telefono = $this->telefono;
        // $moEstudiante->celular = '';
        $moEstudiante->fecha_nacimiento = $this->fecha_nacimiento;
        $moEstudiante->sexo = strtoupper( $this->sexo );
        $moEstudiante->dni = $this->dni;
        $moEstudiante->nombre_escuela = strtoupper( $this->Ie_procedencia );
        $moEstudiante->anio_graduacion = $this->anio_egreso;
        // $moEstudiante->nombre_archivo_foto = '';

        try {
            $this->_estudianteRepository->actualizar($this->idEstudiante, $moEstudiante);
            sweetAlert($this, 'alumno', EstadosEntidadEnum::UPDATED);
        } catch (Exception $ex) {
            toastAlert($this, $ex->getMessage() );
        }
    }

    /***********************************************************  Funciones listeners *************************************************************/
    public function buscar_interno(){
        $this->validateOnly('dni');
        $informacionAlumno = $this->_estudianteRepository->getInformacionEstudiante($this->dni);

        if ($informacionAlumno) {
            $this->dni = $informacionAlumno->dni;
            $this->fecha_nacimiento = $informacionAlumno->fechaNacimiento;
            $this->telefono = $informacionAlumno->telefono;
            $this->distrito = $informacionAlumno->distrito;
            $this->direccion = $informacionAlumno->direccion;
            $this->nombres = $informacionAlumno->nombre;
            $this->apellido_paterno = $informacionAlumno->apPaterno;
            $this->apellido_materno = $informacionAlumno->apMaterno;
            $this->Ie_procedencia = $informacionAlumno->ieProcedencia;
            $this->anio_egreso = $informacionAlumno->anioGraduacion;
            $this->sexo = $informacionAlumno->sexo;
            $this->idEstudiante = $informacionAlumno->idEstudiante;

            $this->emit('alumno-id-encontrado', $informacionAlumno->idEstudiante);
            $this->validate();
        } else {
            toastAlert($this, 'El alumno no pudo ser encontrado', EstadosAlertasEnum::WARNING);
            $this->reset([  "idEstudiante", "fecha_nacimiento", "telefono", "distrito", "direccion",
                            "nombres", "apellido_paterno", "apellido_materno", "Ie_procedencia", "anio_egreso", "sexo" ]);
        }
    }

    public function buscar_externo(){
    }

    public function cargarDataAlumno( string $dniEstudiante ){
        if( is_string($dniEstudiante) && strlen($dniEstudiante)>=8 ){
            $informacionAlumno = $this->_estudianteRepository->getInformacionEstudiante($dniEstudiante);
            if ($informacionAlumno) {
                $this->dni = $informacionAlumno->dni;
                $this->fecha_nacimiento = $informacionAlumno->fechaNacimiento;
                $this->telefono = $informacionAlumno->telefono;
                $this->distrito = $informacionAlumno->distrito;
                $this->direccion = $informacionAlumno->direccion;
                $this->nombres = $informacionAlumno->nombre;
                $this->apellido_paterno = $informacionAlumno->apPaterno;
                $this->apellido_materno = $informacionAlumno->apMaterno;
                $this->Ie_procedencia = $informacionAlumno->ieProcedencia;
                $this->anio_egreso = $informacionAlumno->anioGraduacion;
                $this->sexo = $informacionAlumno->sexo;
                $this->idEstudiante = $informacionAlumno->idEstudiante;
                $this->validate();
            } else  toastAlert($this, "No se pudo encontrar informacion del alumno con dni $dniEstudiante", EstadosAlertasEnum::WARNING);
        }
        else toastAlert($this, "Dni $dniEstudiante con formato incorrecto", EstadosAlertasEnum::WARNING);
    }

    public function enviarDataAutocomplete(){
        $this->emit('data-autocomplete-alumno', (object)[
            "distritos" => $this->lista_distritos,
            "instituciones" => $this->lista_ie_procedencia,
        ]);
    }

    /***********************************************************  Funciones internas *************************************************************/

}
