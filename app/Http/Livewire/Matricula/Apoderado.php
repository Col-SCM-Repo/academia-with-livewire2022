<?php

namespace App\Http\Livewire\Matricula;

use App\Enums\EstadosAlertasEnum;
use App\Enums\EstadosEntidadEnum;
use App\Repository\DistrictRepository;
use App\Repository\OccupationRepository;
use App\Repository\RelativeRepository;
use Exception;
use Livewire\Component;

class Apoderado extends Component
{
    public $formularioApoderado;
    public $idEstudiante, $listaApoderadosEstudiante;

    public $lista_distritos, $lista_ocupaciones;
    private $_distritosRepository, $_ocupacionRepository, $_apoderadoRepository;

    protected $listeners = [
        'reset-form-apoderado' => 'initialState',
        'pagina-cargada-apoderado' => 'enviarDataAutocomplete',
        'change-props-apoderado' => 'setData',
        'cargar-data-apoderado' => 'cargarDataApoderado',
    ];

    protected $rules = [
        'formularioApoderado.dni' => "required | string | min:8",
        'formularioApoderado.f_nac' => "required | date_format:Y-m-d",
        'formularioApoderado.telefono' => "required | string | min: 4",
        'formularioApoderado.distrito' => "required | string ",
        'formularioApoderado.direccion' => "required | string | min: 4",
        'formularioApoderado.parentesco' => "required | in:father,mother,brother,sister,uncle,grandparent,cousin,other",
        'formularioApoderado.nombres' => "required | string | min: 4",
        'formularioApoderado.ap_paterno' => "required | string | min: 4",
        'formularioApoderado.ap_materno' => "required | string | min: 4",
        'formularioApoderado.ocupacion' => "required | string | min: 4",
        //'formularioApoderado.grado_de_instruccion' => "required | string | min: 4",
        'formularioApoderado.sexo' => "required | string | min:4 | max:8",
        'formularioApoderado.estado_marital' => "required | string | min:4",
        'formularioApoderado.relative_id' => "nullable | integer | min:0",
        'idEstudiante' => "required | integer | min:0",
    ];

    public function __construct()
    {
        $this->_apoderadoRepository = new RelativeRepository();
        $this->_distritosRepository = new DistrictRepository();
        $this->_ocupacionRepository = new OccupationRepository();
    }

    public function initialState( )
    {
        $this->reset(['formularioApoderado']);
        $this->formularioApoderado['dni'] = null;
        $this->formularioApoderado['f_nac'] = null;
        $this->formularioApoderado['telefono'] = null;
        $this->formularioApoderado['distrito'] = null;
        $this->formularioApoderado['direccion'] = null;
        $this->formularioApoderado['parentesco'] = null;
        $this->formularioApoderado['nombres'] = null;
        $this->formularioApoderado['ap_paterno'] = null;
        $this->formularioApoderado['ap_materno'] = null;
        $this->formularioApoderado['ocupacion'] = null;
        $this->formularioApoderado['sexo'] = null;
        $this->formularioApoderado['estado_marital'] = null;
        $this->formularioApoderado['student_id'] = null;
        $this->formularioApoderado['relative_id'] = null;
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
        return view('livewire.matricula.apoderado');
    }

    /***********************************************************  CRUD *************************************************************/
    public function create()
    {
        $this->validate();
        $moApoderado = $this->_apoderadoRepository->builderModelRepository();
        $moApoderado->apellido_paterno = formatInputStr( $this->formularioApoderado['ap_paterno'] );
        $moApoderado->apellido_materno = formatInputStr( $this->formularioApoderado['ap_materno'] );
        $moApoderado->nombres = formatInputStr( $this->formularioApoderado['nombres'] );
        $moApoderado->direccion = formatInputStr( $this->formularioApoderado['direccion'] );
        $moApoderado->distrito = formatInputStr( $this->formularioApoderado['distrito'] );
        $moApoderado->telefono = formatInputStr( $this->formularioApoderado['telefono'] );
        //$moApoderado->celular = '';
        //$moApoderado->email = '';
        $moApoderado->fecha_nacimiento = formatInputStr( $this->formularioApoderado['f_nac'] );
        $moApoderado->sexo = formatInputStr( $this->formularioApoderado['sexo'] );
        $moApoderado->dni = formatInputStr( $this->formularioApoderado['dni'] );
        $moApoderado->estado_marital = formatInputStr( $this->formularioApoderado['estado_marital'] );
        //$moApoderado->grado_de_instruccion = '';
        $moApoderado->estudiante_id = $this->idEstudiante ;
        $moApoderado->parentesco = $this->formularioApoderado['parentesco'];
        $moApoderado->ocupacion = formatInputStr( $this->formularioApoderado['ocupacion'] );

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
        $moApoderado->apellido_paterno = formatInputStr( $this->formularioApoderado['ap_paterno'] );
        $moApoderado->apellido_materno = formatInputStr( $this->formularioApoderado['ap_materno'] );
        $moApoderado->nombres = formatInputStr( $this->formularioApoderado['nombres'] );
        $moApoderado->direccion = formatInputStr( $this->formularioApoderado['direccion'] );
        $moApoderado->distrito = formatInputStr( $this->formularioApoderado['distrito'] );
        $moApoderado->telefono = formatInputStr( $this->formularioApoderado['telefono'] );
        //$moApoderado->celular = formatInputStr( $this->formularioApoderado['XXXXX'] );
        //$moApoderado->email = formatInputStr( $this->formularioApoderado['XXXXX'] );
        $moApoderado->fecha_nacimiento = formatInputStr( $this->formularioApoderado['f_nac'] );
        $moApoderado->sexo = formatInputStr( $this->formularioApoderado['sexo'] );
        $moApoderado->dni = formatInputStr( $this->formularioApoderado['dni'] );
        $moApoderado->estado_marital = formatInputStr( $this->formularioApoderado['estado_marital'] );
        //$moApoderado->grado_de_instruccion = formatInputStr( $this->formularioApoderado['grado_de_instruccion']);
        $moApoderado->estudiante_id = $this->idEstudiante ;
        $moApoderado->parentesco = $this->formularioApoderado['parentesco'];
        $moApoderado->ocupacion = formatInputStr( $this->formularioApoderado['ocupacion'] );

        try {
            $this->_apoderadoRepository->actualizar($this->idRelacionApoderado, $moApoderado);
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
        $this->validateOnly('formularioApoderado.dni');
        $this->validateOnly('formularioApoderado.student_id');

        $dni = $this->formularioApoderado['dni'];

        $informacionApoderado = $this->_apoderadoRepository->buscarApoderadoInterno($dni,$this->idEstudiante);
        // dd($informacionApoderado);

        if ($informacionApoderado) {
            $this->formularioApoderado = [
                'dni' => $informacionApoderado->dni,
                'f_nac' => $informacionApoderado->fechaNacimiento,
                'telefono' => $informacionApoderado->telefono,
                'distrito' => $informacionApoderado->distrito,
                'direccion' => $informacionApoderado->direccion,
                'nombres' => $informacionApoderado->nombre,
                'ap_paterno' => $informacionApoderado->apPaterno,
                'ap_materno' => $informacionApoderado->apMaterno,
                'ocupacion' => $informacionApoderado->ocupacion,
                'parentesco' => $informacionApoderado->parentesco,
                'sexo' => $informacionApoderado->sexo,
                'estado_marital' => $informacionApoderado->estado_marital,
                'student_id'=> $this->idEstudiante,
                'relative_id'=> $informacionApoderado->idRelacionApoderado,
            ];
            $this->validate();
        } else {
            $this->initialState();
            toastAlert($this, 'No se encontrò al apoderado.', EstadosAlertasEnum::WARNING);
            $this->formularioApoderado['dni'] = $dni;
            $this->formularioApoderado['student_id'] = $this->idEstudiante;
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

    public function setData( $data )
    {
        $this->formularioApoderado[$data['name']] = $data['value'];
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
