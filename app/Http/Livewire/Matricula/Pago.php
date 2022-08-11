<?php

namespace App\Http\Livewire\Matricula;

use App\Repository\InstallmentRepository;
use App\Repository\PaymentRepository;
use Livewire\Component;

class Pago extends Component
{
    public $matricula_id, $cuotaSeleccionada_id;
    public $formularioPago;
    public $listaCuotas, $historialCuota;

    private $_pagoRepository, $_cuotaRepository;

    protected $rules = [
        'matricula_id' => 'required|numeric|min:0',
        'formularioPago.monto' => 'required|numeric|min:1',
    ];

    protected $listeners = [
        'matricula_id' => 'vincularMatricula'
    ];

    public function __construct()
    {
        $this->_cuotaRepository = new InstallmentRepository();
        $this->_pagoRepository = new PaymentRepository();
    }

    public function initialState()
    {
        $this->reset([]);
    }

    public function render()
    {
        return view('livewire.matricula.pago');
    }

    public function abrirModalPagos()
    {
        openModal($this, '#form-modal-pago');
    }

    // otros en segundo plano 
    public function vincularMatricula($matricula_id)
    {
    }
}
