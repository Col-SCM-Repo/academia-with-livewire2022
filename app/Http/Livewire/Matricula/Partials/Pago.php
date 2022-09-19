<?php

/*  NOMBRE DE BANCO, NUMERO DE OPERACION, MODO DE PAGO (EFECTIVO, DEPOSITO) --- CREAR TABLA DE FECHAS */

namespace App\Http\Livewire\Matricula\Partials;

use App\Enums\{EstadosAlertasEnum, EstadosEntidadEnum};
use App\Repository\{EnrollmentRepository, InstallmentRepository, PaymentRepository};
use Exception;
use Livewire\Component;

class Pago extends Component
{
    public $cuota_id;
    public $monto_pendiente_cuota, $monto_pagar, $modo_pago, $nombre_banco, $numero_operacion;

    public $matricula_id;
    public $cuotas, $historial;
    public $historial_tipo_cuota, $historial_cuota_id;

    private $_pagoRepository, $_cuotaRepository, $_matriculaRepository;

    protected $rules = [
        'matricula_id' => 'required|integer|min:0',

        'cuota_id' => 'required|integer|min:1',
        'monto_pendiente_cuota' => 'required|numeric|min:0',
        'monto_pagar' => 'required|numeric|min:1',
        'modo_pago' => 'required|string|in:cash,deposit',
        'nombre_banco' => 'nullable|required|string',
        'numero_operacion' => 'nullable|required|string',
    ];

    protected $listeners = [
        'anular-pago' => 'anularPago',
        'pagos-matricula-actualizados' => 'cargarDataPagos'
    ];

    public function __construct(){
        $this->_cuotaRepository = new InstallmentRepository();
        $this->_pagoRepository = new PaymentRepository();
        $this->_matriculaRepository = new EnrollmentRepository();
    }

    public function mount(){
        self::initialState();
    }

    public function initialState(){
        $this->reset(['cuota_id', 'monto_pendiente_cuota', 'monto_pagar', 'modo_pago', 'nombre_banco', 'numero_operacion']);
        $this->reset(['historial_tipo_cuota', 'historial_cuota_id' ]);
    }

    public function render()
    {
        toastAlert($this, 'CARGANDO RENDER PAGOS','warning' );
        $this->cuotas = $this->matricula_id? (array) $this->_cuotaRepository->informacionPagosCuotasConNotas($this->matricula_id):null;
        if($this->historial_tipo_cuota &&  $this->historial_cuota_id){
            $this->historial = (array) $this->cuotas[$this->historial_tipo_cuota][$this->historial_cuota_id];
        }
        return view('livewire.matricula.partials.pago');
    }

    /***********************************************************  CRUD *************************************************************/
    public function pagar()
    {
        $this->validate();
        $modelPago = self::buildModelPago();
        try {
            $pagos = $this->cuota_id ? $this->_pagoRepository->pagarMatricula($modelPago) : $this->_pagoRepository->pagarCiclo($modelPago);
            self::initialState();
            if(count($pagos)>0){
                sweetAlert($this, 'pago', EstadosEntidadEnum::CREATED);
                toastAlert($this, count($pagos).' facturas de pagos fueron registrados', EstadosAlertasEnum::SUCCESS );
                openModal($this, '#form-modal-pago', false);
            }
            else toastAlert($this, 'Ocurrio un error sin identificar al registrar el pago');
        } catch (Exception $err) { toastAlert($this, $err->getMessage()); }
    }

    public function anularPago( int $array_index )
    {
        try {
            $pago = $this->historial['pagos'][$array_index];
            $this->_pagoRepository->anularPago($pago['id']);
            sweetAlert($this, 'pago', EstadosEntidadEnum::DELETED);

        } catch (Exception $err) { toastAlert($this, $err->getMessage()); }
    }

    /***********************************************************  Funciones listeners *************************************************************/
    public function abrirModalPagos( string $tipoCuota = 'ciclo' )
    {
        if($this->cuotas){
            dd($this->cuotas);
            $this->reset('formularioPago');
            switch ($tipoCuota) {
                case 'matricula':
                        $index= array_key_first($this->cuotas['matricula']);
                        $cuotaMatricula = $this->cuotas['matricula'][$index];
                        $this->formularioPago['cuota_id'] = $cuotaMatricula['id'];
                        $this->formularioPago['monto_pendiente_cuota'] = $cuotaMatricula['monto_cuota'];
                        $this->formularioPago['monto_pagar'] = $cuotaMatricula['monto_cuota'];
                        openModal($this,'#form-modal-pago');
                    break;
                case 'ciclo':
                    $this->formularioPago['cuota_id'] = null;
                    $this->formularioPago['monto_pendiente_cuota'] = $this->cuotas['monto_deuda_pendiente'];
                    $this->formularioPago['monto_pagar'] = 0;
                    openModal($this,'#form-modal-pago');
                    break;
                default:
                    toastAlert($this, 'No se puede identificar el tipo de cuota a pagar', EstadosAlertasEnum::WARNING);
                break;
            }
        }
    }

    public function abrirModalHistorial( string $tipoCuota, int $cuota_id )
    {
        if($this->cuotas){
            switch ($tipoCuota) {
                case 'matricula':
                case 'ciclo':
                    $this->historial_tipo_cuota = $tipoCuota;
                    $this->historial_cuota_id = $cuota_id;
                    $this->reset('historial');
                    $this->historial = $this->cuotas[$tipoCuota][$cuota_id];
                    // dd($this->historial);
                    openModal($this,'#form-modal-historial');
                    break;
                default:
                    toastAlert($this, 'No se puede identificar la cuota', EstadosAlertasEnum::WARNING);
                break;
            }
        }
    }

    public function cargarDataPagos( int $matricula_id ){
        self::initialState();
        $matricula = $this->_matriculaRepository::find($matricula_id);
        if($matricula ) $this->matricula_id = $matricula->id;
        else toastAlert($this, "No se encontro la matricula con id $matricula_id");
        /* dd( $matricula); */
    }

    /***********************************************************  Funciones internas *************************************************************/

    private function buildModelPago(){
        $modeloPago = $this->_pagoRepository->builderModelRepository();
        $modeloPago->matricula_id = null;

        $modeloPago->cuota_id = $this->cuota_id;
        $modeloPago->montoPagado = $this->monto_pagar;
        $modeloPago->modo_pago = $this->modo_pago;
        $modeloPago->nombre_banco = $this->nombre_banco;
        $modeloPago->numero_operacion = $this->numero_operacion;
        return $modeloPago;
    }
}
