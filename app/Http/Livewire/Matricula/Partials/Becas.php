<?php

namespace App\Http\Livewire\Matricula\Partials;

use App\Repository\EnrollmentRepository;
use App\Repository\ScholarshipRepository;
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
        'formularioBeca.descuento'=>'required|numeric'
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
        return view('livewire.matricula.partials.becas');
    }

    // Funciones crud
    public function agregarBeca(){
        $this->validate();
        dd($this->formularioBeca);
    }

    public function editarBeca(){
        $this->validate();
        dd($this->formularioBeca);
    }

    public function eliminarBeca( int $becaId ){
        dd('Evento eliminar', $becaId);
    }


    // Funciones de listeners
    public function abrirModal( ){
        if($this->matriculaId){
            $matricula =$this->_matriculaRepository::find($this->matriculaId);
            if(!$matricula) toastAlert($this, 'No se encontro informacion de la matricula');

            self::initialState();
            $this->listaBecasRegistradas = $matricula->scholarships;
            $this->montoEvaluar = $matricula->period_cost;
            $this->descuento = 0;

            openModal($this, '#form-modal-becas');
        }
        else toastAlert($this, "No se encontro el codigo de matricula");
    }

    public function cargarDataBeca ( int $becaId ){
        dd("cargando beca", $this->listaBecasRegistradas[$becaId]);
    }

    public function aplicarBeca(){
        $this->validate();
        dd("Aplicando beca", $this->formularioBeca);
    }

    // funciones internas

    public function updatedFormularioBeca($value){
        if( $this->formularioBeca['tipo'] )
            $this->formularioBeca['valor'] = $this->listaBecasDisponibles[$this->formularioBeca['tipo']]['valor'];

    }

    public function matriculaEncontrada( int $matriculaId ){
        $this->matriculaId=$matriculaId;
    }


}
