<?php

namespace App\Http\Livewire\Evaluacion;

use App\Enums\EstadosAlertasEnum;
use App\Models\Type_code;
use App\Repository\StudentExamCodesRepository;
use Exception;
use Livewire\Component;

class Revision extends Component
{
    // formulario
    public $estaAgregandoAlumno;
    public $codigo, $nivel, $aula, $apellidos, $nombres;

    // tabla
    public $listaCodigosEstudiantes;
    public $nivelSeleccionado,$nivelSeleccionadoId;

    public $listaNiveles;
    private $_estudianteCodigosExamenRepository;

    public function __construct()
    {
        $this->_estudianteCodigosExamenRepository = new StudentExamCodesRepository();
    }

    public function initialState(){
        $this->reset([ 'nivelSeleccionado', 'nivelSeleccionadoId' ]);
    }

    public function mount(){
        self::initialState();
        $this->listaNiveles = Type_code::all();
    }

    public function render()
    {
        if($this->nivelSeleccionado){
            $this->listaCodigosEstudiantes = $this->_estudianteCodigosExamenRepository->estudiantesRegistrados($this->nivelSeleccionadoId);
        }
        return view('livewire.evaluacion.revision');
    }

    public function onClickNivel( $nivel_nombre, $nivel_id=null ){
        $this->nivelSeleccionado    = $nivel_nombre;
        $this->nivelSeleccionadoId  = $nivel_id;
        $this->reset([ 'estaAgregandoAlumno', 'codigo', 'nivel', 'aula', 'apellidos', 'nombres' ]);
    }

    public function onClickGenerarCodes (){
        try {
            $this->_estudianteCodigosExamenRepository->generarCodigosExamen();
            toastAlert($this, 'Codigos generados correctamente', EstadosAlertasEnum::SUCCESS);
        } catch (Exception $err) {
            toastAlert($this, $err->getMessage());
        }
    }

    public function onClickResetearCodes (){
        try {
            $cantidad = $this->_estudianteCodigosExamenRepository->resetearCodigosExamen();
            toastAlert($this, "$cantidad codigos eliminados. ", EstadosAlertasEnum::SUCCESS);
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
        try {
            $this->_estudianteCodigosExamenRepository->createEstudianteCodigoExamen(strtoupper($this->apellidos), strtoupper($this->nombres), $this->codigo);
            toastAlert($this, 'Se registró el estudiante libre correctamente', EstadosAlertasEnum::SUCCESS);
            $this->reset([ 'estaAgregandoAlumno', 'codigo', 'nivel', 'aula', 'apellidos', 'nombres' ]);
        } catch (Exception $err) {
            toastAlert($this, $err->getMessage());
        }
    }

}
