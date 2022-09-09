<?php

namespace App\Http\Livewire\Matricula\Partials;

use App\Enums\EstadosEntidadEnum;
use App\Enums\EstadosMatriculaEnum;
use App\Repository\EnrollmentRepository;
use App\Repository\ScholarshipRepository;
use Exception;
use Livewire\Component;

class Becas extends Component
{
    public $listaBecasDisponibles;
    public $listaBecasRegistradas, $matriculaId;
    public $formularioBeca, $idBeca;

    public $montoEvaluar, $descuento;

    private $_becaRepository, $_matriculaRepository;

    protected $listeners = [
        'matricula-encontrada' => 'matriculaEncontrada'
    ];
    protected $rules = [
        'formularioBeca.tipo'=>'required|integer|min:1',
        'formularioBeca.descripcion'=>'required|string',
        'formularioBeca.valor'=>'nullable | required|numeric | min:1',
        'formularioBeca.descuento'=>' numeric | min:1'
    ];

    public function __construct()
    {
        $this->_becaRepository = new ScholarshipRepository();
        $this->_matriculaRepository = new EnrollmentRepository();
    }

    public function initialState(){
        $this->reset([ 'formularioBeca', 'idBeca' ]);
        $this->formularioBeca['tipo']=null;
        $this->formularioBeca['descripcion']=null;
        $this->formularioBeca['descuento']=null;
        $this->formularioBeca['valor']=null;

    }

    public function mount(){
        $this->listaBecasDisponibles = $this->_becaRepository->listaTiposBecas();
        $this->montoEvaluar=0;
        $this->descuento=0;
        self::initialState();

        $this->matriculaId=100;
    }

    public function render()
    {
        if($this->matriculaId){
            $this->listaBecasRegistradas = $this->_becaRepository->becasRegistradasMatricula($this->matriculaId);
            $descuento = 0;
            foreach ($this->listaBecasRegistradas as $beca) {
                $descuento+= $beca['descuento'];
            }
            $this->descuento = $descuento;
        }
        return view('livewire.matricula.partials.becas');
    }

    // Funciones crud
    public function agregarBeca(){
        $this->validate();

        $modeloBeca = $this->_becaRepository->builderModel();
        $modeloBeca->idMatricula = $this->matriculaId;
        $modeloBeca->tipoBeca_id = $this->formularioBeca['tipo'];
        $modeloBeca->descripcion = formatInputStr($this->formularioBeca['descripcion']);
        $modeloBeca->parametroDescuento = $this->formularioBeca['valor'];
        $modeloBeca->costoCiclo = $this->montoEvaluar;
        try {
            $this->_becaRepository->becar($modeloBeca);
            sweetAlert($this, 'beca', EstadosEntidadEnum::CREATED );
            self::initialState();
        } catch (Exception $e ) {
            toastAlert($this, $e->getMessage());
        }
    }

    public function editarBeca(){
        $this->validate();

        $modeloBeca = $this->_becaRepository->builderModel();
        $modeloBeca->tipoBeca_id = $this->formularioBeca['tipo'];
        $modeloBeca->descripcion = formatInputStr($this->formularioBeca['descripcion']);
        $modeloBeca->parametroDescuento = $this->formularioBeca['valor'];
        $modeloBeca->costoCiclo = $this->montoEvaluar;

        try {
            $this->_becaRepository->editarBeca($this->idBeca, $modeloBeca);
            sweetAlert($this, 'beca', EstadosEntidadEnum::UPDATED);
            self::initialState();
        } catch (Exception $e ) {
            toastAlert($this, $e->getMessage());
        }
    }

    public function eliminarBeca( int $becaId ){
        try {
            self::initialState();
            $this->_becaRepository->eliminarBeca($becaId);
            sweetAlert($this, 'beca', EstadosEntidadEnum::DELETED);
        } catch (Exception $e ) {
            toastAlert($this, $e->getMessage());
        }
    }


    // Funciones de listeners
    public function abrirModal( ){
        if($this->matriculaId){
            $matricula =$this->_matriculaRepository::find($this->matriculaId);
            if(!$matricula) toastAlert($this, 'No se encontro informacion de la matricula');

            self::initialState();
            $this->montoEvaluar = $matricula->period_cost;
            $this->descuento = 0;

            openModal($this, '#form-modal-becas');
        }
        else toastAlert($this, "No se encontro el codigo de matricula");
    }

    public function cargarDataBeca ( int $beca_id ){
        self::initialState();

        $this->idBeca = $beca_id;
        $beca = $this->_becaRepository::find($beca_id);
        if($beca){
            $this->formularioBeca['tipo']=$beca->type_scholarship_id;
            $this->formularioBeca['descripcion']=$beca->description;
            $this->formularioBeca['descuento']=$beca->discount;
            $this->formularioBeca['valor']=$beca->parameter_discount;

            $this->validate();
        }
        else toastAlert($this, "No se encontro la beca");
    }

    public function aplicarBeca(){
        if(round($this->descuento) > round($this->montoEvaluar) ){
            toastAlert($this, 'Error, el monto a evaluar NO puede ser menor al costo del ciclo');
            return;
        }

        $matricula = $this->_matriculaRepository::find($this->matriculaId);
        $matricula->period_cost_final = $this->descuento;
        $matricula->status = EstadosMatriculaEnum::PENDIENTE_ACTIVACION;
        $matricula->save();


        sweetAlert($this, 'matricula', EstadosEntidadEnum::UPDATED);
        openModal($this, '#form-modal-becas', false);
    }

    // funciones internas
    public function updated($name, $value){
        switch ($name) {
            case 'formularioBeca.tipo':
                $this->formularioBeca['valor'] = $this->listaBecasDisponibles[$this->formularioBeca['tipo']]['valor'];
                if($this->formularioBeca['valor'])
                    $this->formularioBeca['descuento'] = $this->_becaRepository->calcularDescuento($this->formularioBeca['tipo'], $this->montoEvaluar, $this->formularioBeca['valor']);
                else
                    $this->formularioBeca['descuento'] = "";
                break;
            case 'formularioBeca.valor':
                    if( $value && $value != ''){
                        $this->formularioBeca['descuento'] = $this->_becaRepository->calcularDescuento($this->formularioBeca['tipo'], $this->montoEvaluar, $this->formularioBeca['valor']);
                    }
                break;
        }
    }


    public function matriculaEncontrada( int $matriculaId ){
        $this->matriculaId=$matriculaId;
    }


}
