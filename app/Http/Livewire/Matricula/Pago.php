<?php

namespace App\Http\Livewire\Matricula;

use App\Enums\EstadosEntidadEnum;
use App\Repository\InstallmentRepository;
use App\Repository\PaymentRepository;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Pago extends Component
{
    public $matricula_id;
    public $formularioPago, $fragmentarCuota;
    public $cuotas;

    private $_pagoRepository, $_cuotaRepository;

    protected $rules = [
        'matricula_id' => 'required|numeric|min:0',
        'formularioPago.cuota_id' => 'required|numeric|min:1',
        'formularioPago.deuda_pendiente' => 'required|numeric|min:1',
        'formularioPago.costo_cuota' => 'required|numeric|min:1',
        //'formularioPago.monto' => 'required|numeric|min:1',
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
        $this->matricula_id = 15;
        $this->cuotas = $this->_cuotaRepository->getInformacionPagosYCuotas($this->matricula_id);
        // Log::debug((array) $this->cuotas);
        return view('livewire.matricula.pago');
    }

    public function abrirModalPagos($cuotaSeleccionada_id, $fragmentar = true)
    {
        Log::debug($cuotaSeleccionada_id);
        Log::debug($fragmentar ? 'true' : 'false');

        $cuotaSeleccionada = (object) ($fragmentar ? $this->cuotas['ciclo'][$cuotaSeleccionada_id] : $this->cuotas['matricula'][$cuotaSeleccionada_id]);
        if ($cuotaSeleccionada) {
            $this->fragmentarCuota = $fragmentar;
            $this->formularioPago['cuota_id'] = $cuotaSeleccionada->id;
            $this->formularioPago['deuda_pendiente'] = $this->cuotas['monto_deuda_pendiente'];
            $this->formularioPago['costo_cuota'] = $cuotaSeleccionada->monto_cuota;
            openModal($this, '#form-modal-pago');
        } else
            toastAlert($this, 'Error, no se pudo cargar la cuota en el formulario');
    }


    public function pagar()
    {
        $this->validate();
        $pagoRegistrado = true;
        if ($pagoRegistrado) {
            sweetAlert($this, 'pago', EstadosEntidadEnum::CREATED);

            openModal($this, '#form-modal-pago', false);
            // self::initialState();
        } else
            toastAlert($this, 'Error al registrar pago');
    }

    // otros en segundo plano 
    public function vincularMatricula($matricula_id)
    {
        self::initialState();
        $this->matricula_id = $matricula_id;
    }
}
