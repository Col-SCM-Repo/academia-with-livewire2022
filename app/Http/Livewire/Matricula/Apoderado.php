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
    public $idRelacionApoderado, $formularioApoderado;

    public $lista_distritos, $lista_ocupaciones;
    private $_distritosRepository, $_ocupacionRepository, $_apoderadoRepository;
    private $componenteExterno;

    protected $listeners = [
        //    'reset-form-alumno' => 'initialState',
        'pagina-cargada-apoderado' => 'enviarDataAutocomplete',
        'change-props-apoderado' => 'setData',
        'student-found' => 'setData',
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
        'formularioApoderado.student_id' => "required | integer | min:0",

        'idRelacionApoderado' => 'required | integer | min:0'
    ];

    public function __construct()
    {
        $this->_apoderadoRepository = new RelativeRepository();
        $this->_distritosRepository = new DistrictRepository();
        $this->_ocupacionRepository = new OccupationRepository();
    }

    public function mount($ambito = 0) // 1 = externo  <> 0 = componente interno
    {
        self::initialState();
        $this->lista_distritos =  $this->_distritosRepository->listaDistritos();
        $this->lista_ocupaciones = $this->_ocupacionRepository->listaOcupaciones();
        $this->componenteExterno = $ambito == 1;
    }

    public function render()
    {
        return view('livewire.matricula.apoderado');
    }

    public function initialState()
    {
        $this->reset(['formularioApoderado', 'idRelacionApoderado']);
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
    }

    /***********************************************************  CRUD *************************************************************/
    public function create()
    {
        $this->validateOnly('formularioApoderado');
        //$apoderadoCreado = $this->_apoderadoRepository->registrarApoderado(convertArrayUpperCase($this->formularioApoderado));

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
        $moApoderado->estudiante_id = $this->formularioApoderado['student_id'] ;
        $moApoderado->parentesco = $this->formularioApoderado['parentesco'];
        $moApoderado->ocupacion = formatInputStr( $this->formularioApoderado['ocupacion'] );

        try {
            $apoderado = $this->_apoderadoRepository->registrar($moApoderado);
            $this->idRelacionApoderado  = $apoderado->id;
            sweetAlert($this, 'apoderado', EstadosEntidadEnum::CREATED);
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
        $moApoderado->estudiante_id = $this->formularioApoderado['student_id'] ;
        $moApoderado->parentesco = $this->formularioApoderado['parentesco'];
        $moApoderado->ocupacion = formatInputStr( $this->formularioApoderado['ocupacion'] );

        try {
            $this->_apoderadoRepository->actualizar($this->idRelacionApoderado, $moApoderado);
            sweetAlert($this, 'apoderado', EstadosEntidadEnum::UPDATED);
        } catch (Exception $err) {
            toastAlert( $this, $err->getMessage());
        }
        // CORREGIR BLOQUEAR DNI AL BUSCAR APODERADO
    }

    /* public function updated($name, $value)
    {
        dd($name, $value);
    } */

    /***********************************************************  Funciones listeners *************************************************************/
    public function buscar_interno()
    {
        $this->validateOnly('formularioApoderado.dni');
        $this->validateOnly('formularioApoderado.student_id');

        $dni = $this->formularioApoderado['dni'];
        $student_id = $this->formularioApoderado['student_id'];

        $informacionApoderado = $this->_apoderadoRepository->getInformacionApoderado($dni,$student_id);
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
                'student_id'=> $student_id
            ];
            $this->idRelacionApoderado  = $informacionApoderado->idRelacionApoderado;
            //$this->emitTo('matricula.matricula', 'relative-found', (object)[ 'name' => 'relative_id', 'value' => $informacionApoderado->idRelacionApoderado]);
            $this->validate();
        } else {
            $this->initialState();
            toastAlert($this, 'No se encontrò al apoderado.', EstadosAlertasEnum::WARNING);
            $this->formularioApoderado['dni'] = $dni;
            $this->formularioApoderado['student_id'] = $student_id;
        }
    }

    public function enviarDataAutocomplete()
    {
        $this->emit('data-autocomplete-apoderado', (object)[
            "distritos" => $this->lista_distritos,
            "ocupaciones" => $this->lista_ocupaciones,
        ]);
    }

    public function setData( $data )
    {
        $this->formularioApoderado[$data['name']] = $data['value'];
    }

    /***********************************************************  Funciones internas *************************************************************/

}
