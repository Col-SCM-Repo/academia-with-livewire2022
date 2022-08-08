<?php

namespace App\Http\Livewire\Mantenimiento\CiclosAulas;

use App\Enums\EstadosEntidadEnum;
use App\Repository\LevelRepository;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class Niveles extends Component
{
    public $formularioNivel, $nivelEditar_id;
    public $lista_niveles;

    private $_nivelRepository;

    protected $rules = [
        "formularioNivel.descripcion" => '',
        "formularioNivel.costo" => 'required|numeric|min:100',
        "formularioNivel.fInicio" => 'required|date',
        "formularioNivel.fFin" => 'required|date',
        "formularioNivel.estado" => 'required|integer|min:0',
    ];

    protected $listeners = [
        'periodo-actualizado' => 'render'
    ];

    public function __construct()
    {
        $this->_nivelRepository = new LevelRepository();
    }

    public function initialState()
    {
        $this->reset(['formularioNivel']);
    }

    public function modalOpenEdit($nivel_id)
    {
        self::initialState();
        $nivelTemp = $this->_nivelRepository->informacionNivel($nivel_id);
        if ($nivelTemp) {
            $this->nivelEditar_id = $nivel_id;
            $this->formularioNivel = [
                'descripcion' =>  $nivelTemp->description,
                'costo' =>  $nivelTemp->price,
                'fInicio' =>  $nivelTemp->start_date,
                'fFin' =>  $nivelTemp->end_date,
                'estado' => $nivelTemp->status,

            ];
            openModal($this, '#form-modal-nivel');
            $this->validateOnly('');
        } else {
            toastAlert($this, 'Error al actualizar el nivel');
        }
    }

    public function update()
    {
        $this->validate();
        if ($this->_nivelRepository->actualizarNivel($this->nivelEditar_id, (object)$this->formularioNivel)) {
            $this->emitTo(Aulas::class, 'nivel-actualizado');
            openModal($this, '#form-modal-nivel', false);
            sweetAlert($this, 'nivel', EstadosEntidadEnum::UPDATED);
        }
    }

    public function render()
    {
        if (Session::has('periodo')) {
            $this->lista_niveles = $this->_nivelRepository->buscarNiveles(Session::get('periodo')->id);
        } else
            $this->lista_niveles  = [];

        return view('livewire.mantenimiento.ciclos-aulas.niveles');
    }
}
