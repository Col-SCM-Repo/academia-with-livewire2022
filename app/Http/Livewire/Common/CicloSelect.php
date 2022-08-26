<?php

namespace App\Http\Livewire\Common;

use App\Enums\EstadosEnum;
use App\Repository\PeriodoRepository;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class CicloSelect extends Component
{
    public $periodoId;
    private $_periodoRepository;

    public function __construct(){
        $this->_periodoRepository = new PeriodoRepository();
    }

    public function mount()
    {
        $this->periodos = $this->_periodoRepository->listaPeriodos(EstadosEnum::ACTIVO);
        if (Session::has('periodo'))
            $this->periodoId = Session::get('periodo')->id;
    }

    public function render()
    {
        return view('livewire.common.ciclo-select');
    }

    public function updatedPeriodoId($ciclo_id){
        $cicloVigente = $this->_cicloRepository->cicloVigente($ciclo_id);
        Session::put('periodo', $cicloVigente);
        $this->emit('period-changed');   // Evaluar o eliminar *****
    }
}
