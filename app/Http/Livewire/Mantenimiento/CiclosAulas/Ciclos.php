<?php

namespace App\Http\Livewire\Mantenimiento\CiclosAulas;

use App\Enums\EstadosEntidadEnum;
use App\Repository\PeriodoRepository;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class Ciclos extends Component
{
    public $idCicloSesion;

    public $formularioCiclo;
    public $idCicloEdicion;

    public $lista_ciclos;
    private $_cicloRepository;

    protected $rules = [
        'formularioCiclo.nombre' => 'required|string|min:3',
        'formularioCiclo.anio' => 'required|integer|min:2020',
        'formularioCiclo.activo' => 'required|integer|min:-1|max:1',
    ];

    protected $listeners = [
        "eliminar-ciclo" => "delete"
    ];

    public function __construct()
    {
        $this->_cicloRepository = new PeriodoRepository();
    }

    public function initialState()
    {
        $this->reset(['lista_ciclos', 'formularioCiclo', 'idCicloEdicion']);
    }

    public function mount()
    {
        self::initialState();
        if (Session::has('periodo'))
            $this->idCicloSesion = Session::get('periodo')->id;
    }

    public function render()
    {
        $this->lista_ciclos = $this->_cicloRepository->listaPeriodos();
        return view('livewire.mantenimiento.ciclos-aulas.ciclos');
    }

    // Crud ciclo 
    public function create()
    {
        toastAlert($this, "Entra validacion");
        $this->validate();
        if ($this->_cicloRepository->registrarPeriodo(convertArrayUpperCase($this->formularioCiclo))) {
            sweetAlert($this, 'ciclo', EstadosEntidadEnum::CREATED);
            openModal($this, '#form-modal-ciclo', false);

            $cicloVigente = $this->_cicloRepository->cicloVigente();
            Session::put('periodo', $cicloVigente);
            $this->idCicloSesion = $cicloVigente->id;

            self::initialState();
            $this->emit('periodo-actualizado');
        } else
            toastAlert($this, 'Error al registrar periodo');
    }

    public function update()
    {
        $this->validate();
        if ($this->_cicloRepository->actualizarPeriodo($this->idCicloEdicion, convertArrayUpperCase($this->formularioCiclo))) {
            sweetAlert($this, 'ciclo', EstadosEntidadEnum::UPDATED);
            openModal($this, '#form-modal-ciclo', false);

            $cicloVigente = $this->_cicloRepository->cicloVigente();
            Session::put('periodo', $cicloVigente);
            $this->idCicloSesion = $cicloVigente->id;

            self::initialState();
            $this->emit('periodo-actualizado');
        } else
            toastAlert($this, 'Error al actualizar periodo');
    }

    public function delete(int $periodo_id)
    {
        if ($this->_cicloRepository->eliminarPeriodo($periodo_id)) {
            sweetAlert($this, 'ciclo', EstadosEntidadEnum::DELETED);

            $cicloVigente = $this->_cicloRepository->cicloVigente();
            Session::put('periodo', $cicloVigente);
            $this->idCicloSesion = $cicloVigente->id;

            $this->emit('periodo-actualizado');
        } else
            toastAlert($this, 'Error al eliminar periodo');
    }

    // Comportamiento MODAL ciclo
    // Nuevo ciclo
    public function nuevoPeriodoModal()
    {
        self::initialState();
        $this->validateOnly("");
        openModal($this, '#form-modal-ciclo');
    }

    // Modificar ciclo
    public function selectedPeriodModal(int $periodo_id)
    {
        $periodoSeleccionado =  $this->_cicloRepository::find($periodo_id);
        if (!$periodoSeleccionado)
            toastAlert($this, 'No se encontro el periodo seleccionado');
        else {
            $this->idCicloEdicion = $periodoSeleccionado->id;
            $this->formularioCiclo = [
                'nombre' => $periodoSeleccionado->name,
                'anio' => $periodoSeleccionado->year,
                'activo' => $periodoSeleccionado->active,
            ];
            $this->validate();
            openModal($this, '#form-modal-ciclo');
        }
    }


    // Comportamiento de elementos
    public function cambiarCiclo()
    {
        $periodoSeleccionado = $this->_cicloRepository::find($this->idCicloSesion);
        if ($periodoSeleccionado) {
            Session::put('periodo', $periodoSeleccionado);
            $this->emit('periodo-actualizado');
        }
        self::render();
    }
}
