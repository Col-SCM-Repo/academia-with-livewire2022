<?php

namespace App\Http\Livewire\Matricula\Partials;

use App\Enums\{EstadosAlertasEnum, EstadosEntidadEnum};
use App\Repository\{DistrictRepository, OccupationRepository, RelativeRepository};
use Exception;
use Livewire\Component;

class Apoderado extends Component
{
    public  $idEstudiante;

    //Formulario Apoderado
    public  $relative_id;
    public  $dni, $fecha_nacimiento, $telefono, $distrito, $direccion,
            $parentesco, $nombres, $apellido_paterno, $apellido_materno, $ocupacion, $sexo, $estado_marital;

    public  $lista_distritos, $lista_ocupaciones;
    private $_distritosRepository, $_ocupacionRepository, $_apoderadoRepository;

    protected $listeners = [
        'reset-form-apoderado' => 'initialState',
        'pagina-cargada-apoderado' => 'enviarDataAutocomplete',
        'change-props-apoderado' => 'setData',
        'cargar-id-estudiante' => 'cambiarIdEstudiante',
        'eliminar-apoderado' => 'delete',
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
        return view('livewire.matricula.partials.apoderado')->with('lista_apoderados', $this->_apoderadoRepository->getListaApoderados($this->idEstudiante));
    }

    /***********************************************************  CRUD *************************************************************/
    public function create()
    {
        $this->validate();
        $modeloApoderado =self::buildModeloApoderado();
        try {
            $this->_apoderadoRepository->registrar($modeloApoderado);
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
        $modeloApoderado =self::buildModeloApoderado();
        try {
            $this->_apoderadoRepository->actualizar($this->relative_id, $modeloApoderado);
            sweetAlert($this, 'apoderado', EstadosEntidadEnum::UPDATED);
            openModal($this, '#form-modal-apoderado', false);
            self::initialState();
        } catch (Exception $err) {
            toastAlert( $this, $err->getMessage());
        }
    }

    public function delete( int $apoderado_id )
    {
        dd($apoderado_id);
        try {
            $this->_apoderadoRepository->eliminar($apoderado_id);
            sweetAlert($this, 'apoderado', EstadosEntidadEnum::DELETED);
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
            self::vincularInformacionApoderado($informacionApoderado);
        } else {
            $this->reset([  'relative_id', 'fecha_nacimiento', 'telefono', 'distrito', 'direccion', 'parentesco', 'nombres', 'apellido_paterno', 'apellido_materno', 'ocupacion', 'sexo', 'estado_marital']);
            toastAlert($this, 'No se encontrò al apoderado.', EstadosAlertasEnum::WARNING);
        }
    }

    public function cambiarIdEstudiante( int $estudiante_id){
        self::initialState();
        $this->idEstudiante = $estudiante_id;
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
        $this->relative_id = $apoderadoId;
        $apoderado = $this->_apoderadoRepository->getApoderadoPorId($apoderadoId);
        if($apoderado) {
            self::vincularInformacionApoderado($apoderado);
            openModal($this, '#form-modal-apoderado');
        }
        else
            toastAlert($this, 'Error al cargar datos del apoderado.', EstadosAlertasEnum::WARNING);
    }

    /***********************************************************  Funciones internas *************************************************************/
    private function buildModeloApoderado(){
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
        return $moApoderado;
    }

    private function vincularInformacionApoderado( object $apoderadoObj ){
        /* $this->idEstudiante= $this->idEstudiante; */                   // eva;liar
        $this->relative_id= $apoderadoObj->idRelacionApoderado;
        $this->dni= $apoderadoObj->dni;
        $this->fecha_nacimiento= $apoderadoObj->fechaNacimiento;
        $this->telefono= $apoderadoObj->telefono;
        $this->distrito= $apoderadoObj->distrito;
        $this->direccion= $apoderadoObj->direccion;
        $this->parentesco= $apoderadoObj->parentesco;
        $this->nombres= $apoderadoObj->nombre;
        $this->apellido_paterno= $apoderadoObj->apPaterno;
        $this->apellido_materno= $apoderadoObj->apMaterno;
        $this->ocupacion= $apoderadoObj->ocupacion;
        $this->sexo= $apoderadoObj->sexo;
        $this->estado_marital= $apoderadoObj->estado_marital;
        $this->validate();
    }

    public function enviarDataAutocomplete()
    {
        $this->emit('data-autocomplete-apoderado', (object)[ "distritos" => $this->lista_distritos, "ocupaciones" => $this->lista_ocupaciones ]);
    }
}
