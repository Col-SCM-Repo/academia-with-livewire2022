<?php

namespace App\Http\Livewire\Matricula;

use App\Repository\InstallmentRepository;
use App\Repository\PaymentRepository;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Pago extends Component
{
    public $matricula_id, $cuotaSeleccionada_id;
    public $formularioPago;

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
        $informacionCuotas = $this->_cuotaRepository->getInformacionPagosYCuotas($this->matricula_id);
        Log::debug((array) $informacionCuotas);
        return view('livewire.matricula.pago')->with('cuotas', $informacionCuotas);
    }

    public function abrirModalPagos()
    {
        openModal($this, '#form-modal-pago');
    }

    // otros en segundo plano 
    public function vincularMatricula($matricula_id)
    {
        self::initialState();
        $this->matricula_id = $matricula_id;
    }
}
