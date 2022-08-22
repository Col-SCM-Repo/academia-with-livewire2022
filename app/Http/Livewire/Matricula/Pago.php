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
    public $formularioPago, $autoFraccionamiento;
    public $cuotas;

    private $_pagoRepository, $_cuotaRepository;

    protected $rules = [
        'matricula_id' => 'required|numeric|min:0',
        'formularioPago.cuota_id' => 'required|numeric|min:1',
        'formularioPago.deuda_pendiente' => '',
        'formularioPago.costo_cuota' => 'required|numeric|min:1',
        //'formularioPago.monto' => 'required|numeric|min:1',
    ];

    protected $listeners = [
        'enrollment-found' => 'setData'
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

    /***********************************************************  CRUD *************************************************************/
    public function pagar()
    {
        $this->validate();
        $modelPago = $this->_pagoRepository->builderModelRepository();
        $modelPago->cuota_id = $this->formularioPago['cuota_id'];
        $modelPago->montoPagado = $this->formularioPago['costo_cuota'];
        $modelPago->autoFraccionamiento = $this->autoFraccionamiento;
        $pagoRegistrado = $this->_pagoRepository->registrarPago($modelPago);

        if ($pagoRegistrado) {
            sweetAlert($this, 'pago', EstadosEntidadEnum::CREATED);

            openModal($this, '#form-modal-pago', false);
            // self::initialState();
        } else
            toastAlert($this, 'Error al registrar pago');
    }

    /***********************************************************  Funciones listeners *************************************************************/
    public function abrirModalPagos($cuotaSeleccionada_id, $autoFraccionamiento = true)
    {
        $cuotaSeleccionada = (object) ($autoFraccionamiento ? $this->cuotas['ciclo'][$cuotaSeleccionada_id] : $this->cuotas['matricula'][$cuotaSeleccionada_id]);
        if ($cuotaSeleccionada) {
            $this->autoFraccionamiento = $autoFraccionamiento;
            $this->formularioPago['cuota_id'] = $cuotaSeleccionada->id;
            $this->formularioPago['deuda_pendiente'] = $this->cuotas['monto_deuda_pendiente'];
            $this->formularioPago['costo_cuota'] = $cuotaSeleccionada->monto_cuota;
            openModal($this, '#form-modal-pago');
        } else
            toastAlert($this, 'Error, no se pudo cargar la cuota en el formulario');
    }

    public function setData($nuevaData)
    {
        self::initialState();
        $this->formularioPago[$nuevaData['name']] = $nuevaData['value'];
    }

    /***********************************************************  Funciones internas *************************************************************/


}
