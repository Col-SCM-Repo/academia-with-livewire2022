<?php

namespace App\Http\Livewire\Matricula\Partials;

use App\Enums\EstadosAlertasEnum;
use App\Enums\EstadosEntidadEnum;
use App\Repository\DistrictRepository;
use App\Repository\OccupationRepository;
use App\Repository\RelativeRepository;
use Exception;
use Livewire\Component;

class Apoderado extends Component
{
    // $formularioApoderado
    public  $idEstudiante, $listaApoderadosEstudiante;
    public  $relative_id, $dni, $fecha_nacimiento, $telefono, $distrito, $direccion, 
            $parentesco,$apellido_paterno, $apellido_materno, $ocupacion, $sexo, $estado_marital;

    public  $lista_distritos, $lista_ocupaciones;
    private $_distritosRepository, $_ocupacionRepository, $_apoderadoRepository;

    protected $listeners = [
        'reset-form-apoderado' => 'initialState',
        'pagina-cargada-apoderado' => 'enviarDataAutocomplete',
        'change-props-apoderado' => 'setData',
        'cargar-data-apoderado' => 'cargarDataApoderado',
    ];

    protected $rules = [
        'idEstudiante' => "required | integer | min:0",
        
        'relative_id' => "nullable | integer | min:0",
        'dni' => "required | string | min:8",
        'fecha_nacimiento' => "required | date_format:Y-m-d",
        'telefono' => "required | string | min: 4",
        'distrito' => "required | string ",
        'direccion' => "required | string | min: 4",
        'parentesco' => "required | in:father,mother,brother,sister,uncle,grandparent,cousin,other",
        'nombres' => "required | string | min: 4",
        'apellido_paterno' => "required | string | min: 4",
        'apellido_materno' => "required | string | min: 4",
        'ocupacion' => "required | string | min: 4",
        'sexo' => "required | string | min:4 | max:8",
        'estado_marital' => "required | string | min:4",
        //'grado_de_instruccion' => "required | string | min: 4",
    ];

    public function __construct()
    {
        $this->_apoderadoRepository = new RelativeRepository();
        $this->_distritosRepository = new DistrictRepository();
        $this->_ocupacionRepository = new OccupationRepository();
    }

    public function initialState( )
    {
        $this->reset([  'relative_id', 'dni', 'fecha_nacimiento', 'telefono', 'distrito', 'direccion', 'parentesco', 
                        'nombres', 'apellido_paterno', 'apellido_materno', 'ocupacion', 'sexo', 'estado_marital']);
    }

    public function mount()
    {
        self::initialState();
        $this->lista_distritos =  $this->_distritosRepository->listaDistritos();
        $this->lista_ocupaciones = $this->_ocupacionRepository->listaOcupaciones();
    }

    public function render()
    {
        if($this->idEstudiante)
            $this->listaApoderadosEstudiante = $this->_apoderadoRepository->getListaApoderados($this->idEstudiante);
        return view('livewire.matricula.partials.apoderado');
    }

    /***********************************************************  CRUD *************************************************************/
    public function create()
    {
        $this->validate();
        $moApoderado = $this->_apoderadoRepository->builderModelRepository();
        $moApoderado->apellido_paterno = strtoupper($this->apellido_paterno);
        $moApoderado->apellido_materno = strtoupper($this->apellido_materno);
        $moApoderado->nombres = strtoupper($this->nombres);
        $moApoderado->direccion = strtoupper($this->direccion);
        $moApoderado->distrito = strtoupper($this->distrito);
        $moApoderado->telefono = $this->telefono;
        //$moApoderado->celular = '';
        //$moApoderado->email = '';
        $moApoderado->fecha_nacimiento = $this->fecha_nacimiento;
        $moApoderado->sexo = strtoupper($this->sexo);
        $moApoderado->dni = $this->dni;
        $moApoderado->estado_marital = strtoupper($this->estado_marital);
        //$moApoderado->grado_de_instruccion = '';
        $moApoderado->estudiante_id = $this->idEstudiante ;
        $moApoderado->parentesco = strtoupper($this->parentesco);
        $moApoderado->ocupacion = strtoupper($this->ocupacion);

        try {
            $this->_apoderadoRepository->registrar($moApoderado);
            sweetAlert($this, 'apoderado', EstadosEntidadEnum::CREATED);
            openModal($this, '#form-modal-apoderado', false);
            self::initialState();
        } catch (Exception $err) {
            toastAlert( $this, $err->getMessage());
        }
    }

    public function update()
    {
        $this->validate();
        $moApoderado = $this->_apoderadoRepository->builderModelRepository();
        $moApoderado->apellido_paterno = strtoupper($this->apellido_paterno);
        $moApoderado->apellido_materno = strtoupper($this->apellido_materno);
        $moApoderado->nombres = strtoupper($this->nombres);
        $moApoderado->direccion = strtoupper($this->direccion);
        $moApoderado->distrito = strtoupper($this->distrito);
        $moApoderado->telefono = $this->telefono;
        //$moApoderado->celular = '';
        //$moApoderado->email = '';
        $moApoderado->fecha_nacimiento = $this->fecha_nacimiento;
        $moApoderado->sexo = strtoupper($this->sexo);
        $moApoderado->dni = $this->dni;
        $moApoderado->estado_marital = strtoupper($this->estado_marital);
        //$moApoderado->grado_de_instruccion = '';
        $moApoderado->estudiante_id = $this->idEstudiante ;
        $moApoderado->parentesco = strtoupper($this->parentesco);
        $moApoderado->ocupacion = strtoupper($this->ocupacion);

        try {
            $this->_apoderadoRepository->actualizar($this->relative_id, $moApoderado);
            sweetAlert($this, 'apoderado', EstadosEntidadEnum::UPDATED);
            openModal($this, '#form-modal-apoderado', false);
            self::initialState();
        } catch (Exception $err) {
            toastAlert( $this, $err->getMessage());
        }
    }

    /***********************************************************  Funciones listeners *************************************************************/
    public function buscar_interno()
    {
        $this->validateOnly('dni');
        $this->validateOnly('idEstudiante');

        $informacionApoderado = $this->_apoderadoRepository->buscarApoderadoInterno($this->dni, $this->idEstudiante);
        if ($informacionApoderado) {
            $this->idEstudiante= $this->idEstudiante;                   // eva;liar

            $this->relative_id= $informacionApoderado->idRelacionApoderado;
            $this->dni= $informacionApoderado->dni;
            $this->fecha_nacimiento= $informacionApoderado->fechaNacimiento;
            $this->telefono= $informacionApoderado->telefono;
            $this->distrito= $informacionApoderado->distrito;
            $this->direccion= $informacionApoderado->direccion;
            $this->parentesco= $informacionApoderado->parentesco;
            $this->nombres= $informacionApoderado->nombre;
            $this->apellido_paterno= $informacionApoderado->apPaterno;
            $this->apellido_materno= $informacionApoderado->apMaterno;
            $this->ocupacion= $informacionApoderado->ocupacion;
            $this->sexo= $informacionApoderado->sexo;
            $this->estado_marital= $informacionApoderado->estado_marital;
            $this->validate();
        } else {
            $this->reset([  'relative_id', 'fecha_nacimiento', 'telefono', 'distrito', 'direccion', 'parentesco', 
                            'nombres', 'apellido_paterno', 'apellido_materno', 'ocupacion', 'sexo', 'estado_marital']);
            toastAlert($this, 'No se encontrò al apoderado.', EstadosAlertasEnum::WARNING);
        }
    }

    public function enviarDataAutocomplete()
    {
        $this->emit('data-autocomplete-apoderado', (object)[
            "distritos" => $this->lista_distritos,
            "ocupaciones" => $this->lista_ocupaciones,
        ]);
    }

    public function cargarDataApoderado( int $estudianteId){
        $this->idEstudiante = $estudianteId;
        $this->listaApoderadosEstudiante = $this->_apoderadoRepository->getListaApoderados($estudianteId);
    }

    /***********************************************************  Funciones modal *************************************************************/
    public function nuevoApoderado()
    {
        self::initialState();
        openModal($this, '#form-modal-apoderado');
    }

    public function editarApoderado(int $apoderadoId)
    {
        self::initialState();

        $this->formularioApoderado['relative_id'] = $apoderadoId;
        $apoderado = $this->_apoderadoRepository->getApoderadoPorId( $apoderadoId );
        if($apoderado){
            $this->formularioApoderado = [
                'dni' => $apoderado->dni,
                'f_nac' => $apoderado->fechaNacimiento,
                'telefono' => $apoderado->telefono,
                'distrito' => $apoderado->distrito,
                'direccion' => $apoderado->direccion,
                'nombres' => $apoderado->nombre,
                'ap_paterno' => $apoderado->apPaterno,
                'ap_materno' => $apoderado->apMaterno,
                'ocupacion' => $apoderado->ocupacion,
                'parentesco' => $apoderado->parentesco,
                'sexo' => $apoderado->sexo,
                'estado_marital' => $apoderado->estado_marital,
                'student_id'=> $this->idEstudiante,
                'relative_id'=> $apoderado->idRelacionApoderado,
            ];
        }
        else toastAlert($this, 'Error al cargar datos del apoderado.', EstadosAlertasEnum::WARNING);
        $this->validate();
        openModal($this, '#form-modal-apoderado');
    }
    /***********************************************************  Funciones internas *************************************************************/

}
