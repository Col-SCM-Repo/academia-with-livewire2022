<?php

namespace App\Http\Livewire\Matricula;

use App\Enums\EstadosAlertasEnum;
use App\Enums\EstadosEntidadEnum;
use App\Enums\EstadosEnum;
use App\Repository\EnrollmentRepository;
use App\Repository\InstallmentRepository;
use App\Repository\PaymentRepository;
use Exception;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Pago extends Component
{
    public $matricula_id, $formularioPago;

    //
    public $historial_tipo_cuota, $historial_cuota_id;

    public $cuotas, $historial;
    private $_pagoRepository, $_cuotaRepository, $_matriculaRepository;

    protected $rules = [
        'matricula_id' => 'required|integer|min:0',
        'formularioPago.cuota_id' => 'nullable|integer|min:1',
        'formularioPago.monto_pendiente_cuota' => '',
        'formularioPago.monto_pagar' => 'required|numeric|min:1',
    ];

    protected $listeners = [
        'enrollment-found' => 'setData',
        'anular-pago' => 'anularPago',
        'cargar-data-pagos' => 'cargarDataPagos',
    ];

    public function __construct()
    {
        $this->_cuotaRepository = new InstallmentRepository();
        $this->_pagoRepository = new PaymentRepository();
        $this->_matriculaRepository = new EnrollmentRepository();
    }

    public function mount(){
        self::initialState();
    }

    public function initialState()
    {
        $this->reset(['formularioPago','matricula_id', 'historial_tipo_cuota' ,'historial_cuota_id' ]);
        $this->matricula_id=null;
        $this->formularioPago['cuota_id'] = null;
        $this->formularioPago['monto_pendiente_cuota'] = null;
        $this->formularioPago['monto_pagar'] = null;
    }

    public function render()
    {
        // $this->matricula_id = 1;
        $this->cuotas = $this->matricula_id? $this->_cuotaRepository->getInformacionPagosYCuotas($this->matricula_id) : null ;
        if($this->historial_tipo_cuota &&  $this->historial_cuota_id){
            $this->historial = (array) $this->cuotas[$this->historial_tipo_cuota][$this->historial_cuota_id];
            /* foreach ($this->historial['pagos'] as $key => $pago)
                $this->historial['pagos'][$key] = (array)$pago; */

            // dd($this->historial);
        }

        // Log::debug((array) $this->cuotas);
        return view('livewire.matricula.pago');
    }

    /***********************************************************  CRUD *************************************************************/
    public function pagar()
    {
        $this->validate();
        // dd($this->formularioPago);

        $modelPago = $this->_pagoRepository->builderModelRepository();
        $modelPago->montoPagado = $this->formularioPago['monto_pagar'];
        $modelPago->cuota_id = $this->formularioPago['cuota_id'];
        $modelPago->matricula_id = $this->matricula_id;

        try {
            $pagos = array();
            if($this->formularioPago['cuota_id']==null)
                $pagos = $this->_pagoRepository->pagarCiclo($modelPago);
            else
                $pagos = $this->_pagoRepository->pagarMatricula($modelPago);
            if(count($pagos)>0){
                sweetAlert($this, 'pago', EstadosEntidadEnum::CREATED);
                toastAlert($this, count($pagos).' facturas de pagos fueron registrados', EstadosAlertasEnum::SUCCESS );
                openModal($this, '#form-modal-pago', false);
            }
            else
                throw new Exception('Ocurrio un error sin identificar al registrar el pago');
        } catch (Exception $err) {
            toastAlert($this, $err->getMessage());
        }
    }

    public function anularPago( int $array_index )
    {
        // dd( $array_index, $this->historial );
        try {
            $pago = $this->historial['pagos'][$array_index];
            $this->_pagoRepository->anularPago($pago['id']);
            sweetAlert($this, 'pago', EstadosEntidadEnum::DELETED);

        } catch (Exception $err) {
            toastAlert($this, $err->getMessage());
        }
    }

    /***********************************************************  Funciones listeners *************************************************************/
    public function abrirModalPagos( string $tipoCuota = 'ciclo' )
    {
        //dd($this->cuotas);
        if($this->cuotas){
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

    public function setData($nuevaData)
    {
        self::initialState();
        $this->formularioPago[$nuevaData['name']] = $nuevaData['value'];
    }

    public function cargarDataPagos( int $estudiante_id ){
        self::initialState();
        $ultimaMatricula = $this->_matriculaRepository::where('student_id', $estudiante_id)->where('status', EstadosEnum::ACTIVO)->orderBy('id', 'desc')->first();
        if($ultimaMatricula ){
            $this->matricula_id = $ultimaMatricula->id;
        }

        //dd( $ultimaMatricula);

    }

    /***********************************************************  Funciones internas *************************************************************/


}
