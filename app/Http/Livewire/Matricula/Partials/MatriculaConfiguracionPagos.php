<?php

namespace App\Http\Livewire\Matricula\Partials;

use App\Enums\FormasPagoEnum;
use App\Repository\EnrollmentRepository;
use App\Repository\InstallmentRepository;
use Livewire\Component;

class MatriculaConfiguracionPagos extends Component
{
    // Formulario cuotas
    public $matriculaId;
    public $costoMatricula, $costoCiclo, $costoAbonado, $tipoPago, $numeroCuotas, $detalleCuotas;

    private $_matriculaRepository, $_cuotasRepository;

    protected $listeners = [ ];

    protected $rules = [
        'matriculaId' => 'required|integer|min:1 ',

        'costoMatricula' => 'required|numeric|min:0 ',
        'costoCiclo' => 'required|numeric|min:0|max:4 ',
        'costoAbonado' => 'required|numeric|min:0|max:4 ',
        'tipoPago' => 'required|string|in:cash,credit',
        'numeroCuotas' => 'required|integer|min:0 ',
        'detalleCuotas' => 'nullable|array',

        'detalleCuotas.*.costo' => 'required|numeric|min:1',
        'detalleCuotas.*.fecha' => 'nullable|required|date',
    ];

    public function mount($matricula_id){
        $this->matriculaId = $matricula_id;
    }

    public function __construct()
    {
        $this->_matriculaRepository = new EnrollmentRepository();
        $this->_cuotasRepository = new InstallmentRepository();
    }

    public function initialState(){
        $this->reset([ 'costoMatricula', 'costoCiclo', 'costoAbonado', 'tipoPago', 'numeroCuotas', 'detalleCuotas' ]);
    }

    public function render()
    {
        $matricula_status = $this->matriculaId? $this->_matriculaRepository::find($this->matriculaId)->status : null;
        return view('livewire.matricula.partials.matricula-configuracion-pagos', compact('matricula_status'));
    }

    public function create(){
        $this->validate();
        $modeloPagos = self::buildModeloPagos();
        /* dd('create', $modeloPagos); */
    }

    public function update(){
        $this->validate();
        $modeloPagos = self::buildModeloPagos();
        dd('update', $modeloPagos);
    }

    /* Funciones listeners */
    public function updatedTipoPago( string $nuevoTipoPago ){
        if( $nuevoTipoPago != '' ){
            switch ($nuevoTipoPago) {
                case FormasPagoEnum::CONTADO:
                    $this->costoMatricula = 0;
                    $this->numeroCuotas = 0;
                    $this->detalleCuotas = null;
                    break;
                case FormasPagoEnum::CREDITO:
                    $this->costoMatricula = 50;
                    $this->numeroCuotas = 2;
                    $this->detalleCuotas = null;
                    self::calcularMontoCuotas();
                break;
                default: toastAlert($this, "Entrada $nuevoTipoPago no valida");
            }
            $this->validateOnly('costoMatricula');
            $this->validateOnly('numeroCuotas');
            $this->validateOnly('tipoPago');
        }
        else{
            $this->reset([ 'costoMatricula', 'numeroCuotas', 'detalleCuotas']);
        }
    }

    public function updatedNumeroCuotas( int $numeroCuotas ){
        if(!($numeroCuotas>0 &&$numeroCuotas<5 )){
            toastAlert($this, 'Error, numero de cuotas no valido');
            $this->numeroCuotas=2;
        }
        self::calcularMontoCuotas();
    }
    public function updatedDetalleCuotas($value){
        $this->validateOnly('detalleCuotas');
    }

    public function abrirModalCuotasPago(){
        self::initialState();
        self::cargarCuotasPago();
        openModal($this, '#form-modal-cuotas-pago');
    }

    /* Funciones internas */
    private function cargarCuotasPago(){
        $matricula = $this->_matriculaRepository::find($this->matriculaId);
        if(!$matricula){
            toastAlert($this, 'Error, no se encontrò la matricula');
            return;
        }
        $this->costoCiclo = $matricula->period_cost_final;
        $this->costoAbonado = $matricula->amount_paid;

        $cuotasExistentes = true;       // buscar cuotas de pago existentes
        if($cuotasExistentes){
            $this->costoMatricula = null;
            $this->tipoPago = null;
            $this->numeroCuotas = null;
            $this->detalleCuotas = null;
        }
        else $this->reset(['costoMatricula','tipoPago','numeroCuotas','detalleCuotas']);
    }

    public function calcularMontoCuotas( $indiceArray = null ){
        if($indiceArray!=null ){
            $indiceArray--;
            $sumaMontoCuotas = 0;
            foreach ($this->detalleCuotas as $detalle) $sumaMontoCuotas+=round($detalle['costo'],2);

            $diferencia = $this->costoCiclo - $sumaMontoCuotas;
            $this->detalleCuotas[ count($this->detalleCuotas)-1 ]['costo']+=$diferencia;

            if($this->detalleCuotas[ count($this->detalleCuotas)-1 ]['costo']<=0){
                toastAlert($this, "La ultima cuota de pago no puede ser un monto menor o igual a 0");
                self::calcularMontoCuotas();
            }
        }else{
            $costoCuota = round( round($this->costoCiclo, 2) / $this->numeroCuotas ,2);
            $cuotasAutogeneradas = array();
            for($i =0; $i<$this->numeroCuotas; $i++)
                $cuotasAutogeneradas[] = ["costo" => $costoCuota,"fecha" => null];
            $this->detalleCuotas = $cuotasAutogeneradas;
        }
    }

    private function buildModeloPagos(){
        $moPagos = $this->_cuotasRepository->builderModelRepository();
        $moPagos->matricula_id = $this->matriculaId;
        $moPagos->tipo_pago = $this->tipoPago;
        $moPagos->costo_matricula = $this->costoMatricula;
        $moPagos->costo_ciclo = $this->costoCiclo;
        $moPagos->cuotas = $this->numeroCuotas;
        $moPagos->detalle_cuotas = $this->numeroCuotas>0? $this->detalleCuotas : null;
        return $moPagos;
    }

}
