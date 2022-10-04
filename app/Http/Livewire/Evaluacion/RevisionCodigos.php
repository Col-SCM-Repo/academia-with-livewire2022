<?php

namespace App\Http\Livewire\Evaluacion;

use App\Enums\EstadosAlertasEnum;
use App\Models\Type_code;
use App\Repository\StudentExamCodesRepository;
use Exception;
use Livewire\Component;

class RevisionCodigos extends Component
{
    // formulario
    public $estaAgregandoAlumno;
    public $codigo, $nivel, $aula, $apellidos, $nombres;

    // tabla edicion
    public $estudianteCodeId;

    // tabla
    public $nivelSeleccionado, $busqueda;
    public $listaCodigosEstudiantes;


    public $listaNiveles;
    private $_estudianteCodigosExamenRepository;

    protected $rules  = [
        'codigo' => 'required|string|min:4',
        'nivel' => '',
        'aula' => '',
        'apellidos' => 'required|string|min:4',
        'nombres' => 'required|string|min:4',
        'listaCodigosEstudiantes'=> 'array|nullable',
        'listaCodigosEstudiantes.*.enrollment_code'=> 'required|string|min:4',
        'listaCodigosEstudiantes.*.level'=> '',
        'listaCodigosEstudiantes.*.classroom'=> '',
        'listaCodigosEstudiantes.*.surname'=> 'required|string|min:4',
        'listaCodigosEstudiantes.*.name'=> 'required|string|min:4',
    ];

    public function __construct()
    {
        $this->_estudianteCodigosExamenRepository = new StudentExamCodesRepository();
    }

    public function initialState(){
        $this->reset([ 'estaAgregandoAlumno', 'codigo', 'nivel', 'aula', 'apellidos', 'nombres' ]);
        $this->reset([ 'nivelSeleccionado', 'busqueda' ]);
        $this->reset(['estudianteCodeId']);
    }

    public function mount(){
        self::initialState();
        $this->listaNiveles = Type_code::all();
    }

    public function render()
    {
        toastAlert($this, 'Renderizando evaluaciones.codigos', 'warning');
        if($this->nivelSeleccionado){
            $this->listaCodigosEstudiantes = $this->_estudianteCodigosExamenRepository->estudiantesRegistrados($this->nivelSeleccionado, $this->busqueda);
        }
        return view('livewire.evaluacion.revision-codigos');
    }

    public function onClickNivel( $nivel_nombre ){
        self::initialState();
        $this->nivelSeleccionado    = $nivel_nombre;
    }

    public function onClickGenerarCodes (){
        try {
            $filasAfectadas = $this->_estudianteCodigosExamenRepository->generarCodigosExamen();
            toastAlert($this, $filasAfectadas.' codigos generados', EstadosAlertasEnum::SUCCESS);
            self::initialState();
        } catch (Exception $err) {
            toastAlert($this, $err->getMessage());
        }
    }

    public function onClickResetearCodes (){
        try {
            $filasAfectadas = $this->_estudianteCodigosExamenRepository->resetearCodigosExamen();
            toastAlert($this, "$filasAfectadas codigos eliminados. ", EstadosAlertasEnum::SUCCESS);
            self::initialState();
        } catch (Exception $err) {
            toastAlert($this, $err->getMessage());
        }
    }

    public function onClickAddAluLibre (){
        $this->reset([ 'estaAgregandoAlumno', 'codigo', 'nivel', 'aula', 'apellidos', 'nombres' ]);
        $this->estaAgregandoAlumno = true;
    }

    public function onCancelAlumnoLibre(){
        $this->estaAgregandoAlumno = false;
    }

    public function onAddAlumnoLibre(){
        $this->validate();
        try {
            $this->_estudianteCodigosExamenRepository->createEstudianteCodigoExamen(strtoupper($this->apellidos), strtoupper($this->nombres), $this->codigo);
            toastAlert($this, 'Se registró el estudiante libre correctamente', EstadosAlertasEnum::SUCCESS);
            $this->reset([ 'estaAgregandoAlumno', 'codigo', 'nivel', 'aula', 'apellidos', 'nombres' ]);
        } catch (Exception $err) {
            toastAlert($this, $err->getMessage());
        }
    }

    public function onBtnModoEdicion( int $estudiante_code_id ){
        $this->estudianteCodeId = $estudiante_code_id;
    }

    public function onBtnGuardarEdicion ( int $index ){
        $this->validateOnly('listaCodigosEstudiantes.*.*');
        try {
            $estudianteModificado = $this->listaCodigosEstudiantes[$index];
            $this->_estudianteCodigosExamenRepository->actualizarEstudianteCodeExm($this->estudianteCodeId, $estudianteModificado['name'], $estudianteModificado['surname'], $estudianteModificado['enrollment_code'] );
            $this->reset([ 'estudianteCodeId' ]);
            toastAlert($this, 'Se actualizo la informacion', EstadosAlertasEnum::SUCCESS);
        } catch (Exception $err) {
            toastAlert($this, $err->getMessage());
        }
    }

}
