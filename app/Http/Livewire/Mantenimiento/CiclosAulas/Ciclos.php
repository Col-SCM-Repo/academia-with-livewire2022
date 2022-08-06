<?php

namespace App\Http\Livewire\Mantenimiento\CiclosAulas;

use App\Enums\EstadosAlertas;
use App\Enums\EstadosEntidad;
use App\Repository\PeriodoRepository;
use Livewire\Component;

class Ciclos extends Component
{
    public $anioActual;
    public $formularioCiclo;
    public $lista_ciclos;

    public $idCicloEdicion;
    public $_cicloRepository;

    protected $rules = [
        'formularioCiclo.nombre' => 'required|string|min:3',
        'formularioCiclo.anio' => 'required|integer|min:4',
        'formularioCiclo.activo' => 'required|integer|min:-1|max:1',
    ];


    public function __construct()
    {
        $this->_cicloRepository = new PeriodoRepository();
    }

    public function initialState(){
        $this->reset(['lista_ciclos','formularioCiclo','idCicloEdicion','anioActual']);
    }

    public function mount (){
        self::initialState();
    }

    public function render()
    {
        $this->lista_ciclos = $this->_cicloRepository->listaPeriodos();
        return view('livewire.mantenimiento.ciclos-aulas.ciclos');
    }

    public function selectedPeriod( int $periodo_id ){
        $periodoSeleccionado =  $this->_cicloRepository::find($periodo_id);
        if(!$periodoSeleccionado)
            toastAlert($this, 'No se encontro el periodo seleccionado' );
        else{
            $this->idCicloEdicion = $periodoSeleccionado->id;
            $this->formularioCiclo = [
                'nombre' => $periodoSeleccionado->name ,
                'anio' => $periodoSeleccionado->year ,
                'activo' => $periodoSeleccionado->active ,
            ];
            $this->validate();
            openModal($this, '#form-modal-ciclo');
        }
    }

    public function create(){
        $this->validate();
        $this->_cicloRepository->registrarPeriodo(convertArrayUpperCase($this->formularioCiclo));
        sweetAlert($this, 'ciclo', EstadosEntidad::CREATED);
        openModal($this, '#form-modal-ciclo', false);
        self::initialState();
    }

    public function update(){
        $this->validate();
        if($this->_cicloRepository->actualizarPeriodo($this->idCicloEdicion, convertArrayUpperCase($this->formularioCiclo))){
            sweetAlert($this, 'ciclo', EstadosEntidad::UPDATED);
            openModal($this, '#form-modal-ciclo', false);
            self::initialState();
        }
        else
            toastAlert($this, 'Error al actualizar periodo');
    }

}
