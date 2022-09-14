<?php

namespace App\Http\Livewire\Matricula\Partials;

use App\Enums\EstadosEntidadEnum;
use App\Enums\EstadosMatriculaEnum;
use App\Enums\TipoDescuentosEnum;
use App\Repository\EnrollmentRepository;
use App\Repository\ScholarshipRepository;
use Exception;
use Livewire\Component;

class Becas extends Component
{
    // Formulario becas
    public  $becaId;
    public  $tipoDescuento, $descripcion, $parametroDescuento;

    private $_becaRepository;

    protected $listeners = [
        'matricula-encontrada' => 'matriculaEncontrada',
        'eliminar-descuento' => 'delete',
    ];
    protected $rules = [
        'becaId' => 'nullable |  integer | min:1',
        'tipoDescuento'=>'required | string | in:fixed,percentaje',
        'descripcion'=>' required | string',
        'parametroDescuento'=>'required | numeric | min:1',
    ];

    public function __construct()
    {
        $this->_becaRepository = new ScholarshipRepository();
    }

    public function initialState(){
        $this->reset([ 'becaId','tipoDescuento','descripcion','parametroDescuento']);
    }

    public function mount(){
        self::initialState();
    }

    public function render()
    {
        $this->emit('asignar-eventos-descuentos');
        $listaBecasDisponibles = $this->_becaRepository->listaDescuentos();
        return view('livewire.matricula.partials.becas')->with('becas', $listaBecasDisponibles);
    }

    // Funciones crud
    public function create(){
        $this->validate();
        $modeloDescuento = self::buildModeloDescuento();
        try {
            $this->_becaRepository->registrar($modeloDescuento);
            self::initialState();
            sweetAlert($this, 'beca', EstadosEntidadEnum::CREATED );
            $this->emit('descuentos-actualizados');
        } catch (Exception $e ) {
            toastAlert($this, $e->getMessage());
        }
    }

    public function update(){
        $this->validate();
        $modeloDescuento = self::buildModeloDescuento();
        try {
            $this->_becaRepository->actualizar($this->becaId, $modeloDescuento);
            sweetAlert($this, 'beca', EstadosEntidadEnum::UPDATED);
            self::initialState();
            $this->emit('descuentos-actualizados');
        } catch (Exception $e ) {
            toastAlert($this, $e->getMessage());
        }
    }

    public function delete( int $beca_id ){
        try {
            $this->_becaRepository->eliminar($beca_id);
            self::initialState();
            sweetAlert($this, 'beca', EstadosEntidadEnum::DELETED);
            $this->emit('descuentos-actualizados');
        } catch (Exception $e ) {
            toastAlert($this, $e->getMessage());
        }
    }

    // Funciones de listeners
    public function abrirModal( ){
        self::initialState();
        openModal($this, '#form-modal-becas');
    }

    public function descuentoSeleccionado ( int $beca_id ){
        self::initialState();
        $beca = $this->_becaRepository::find($beca_id);
        if($beca) self::vincularInformacionDescuento($beca);
        else toastAlert($this, "No se encontro la beca");
    }

    // Funciones internas
    public function buildModeloDescuento(){
        $moBeca = $this->_becaRepository->builderModel();
        $moBeca->tipoDescuento = $this->tipoDescuento;
        $moBeca->descripcion = strtoupper($this->descripcion);
        $moBeca->parametroDescuento = $this->parametroDescuento;
        return $moBeca;
    }

    public function vincularInformacionDescuento( object $beca ){
        $this->becaId = $beca->id;
        $this->tipoDescuento = $beca->type_scholarship;
        $this->descripcion = $beca->description;
        $this->parametroDescuento = $beca->parameter_discount;
        $this->validate();
    }

}
