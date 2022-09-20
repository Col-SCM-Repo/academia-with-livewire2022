<?php

namespace App\Http\Livewire\Matricula\Partials;

use App\Enums\{EstadosAlertasEnum, EstadosEntidadEnum, ModoPagoEnum};
use App\Repository\{InstallmentRepository, PaymentRepository};
use Exception;
use Livewire\Component;

class Pago extends Component
{
    public $cuota_id;
    public $monto_pendiente_cuota, $monto_pagar, $modo_pago, $nombre_banco, $numero_operacion;

    public $matricula_id;
    public $historial;

    public $general_deuda, $general_pagado, $matricula_deuda, $matricula_pagado, $ciclo_deuda, $ciclo_pagado;

    private $_pagoRepository, $_cuotaRepository;

    protected $rules = [
        'matricula_id' => 'required|integer|min:0',

        'cuota_id' => 'nullable|integer|min:1',
        'monto_pendiente_cuota' => 'numeric|min:0',
        'monto_pagar' => 'required|numeric|min:1',
        'modo_pago' => 'required|string|in:cash,deposit',
        'nombre_banco' => 'required | string | min:3',
        'numero_operacion' => 'required | string | min:3',
    ];

    protected $listeners = [
        'anular-pago' => 'anularPago',
        'pagos-matricula-actualizados' => 'cargarDataPagos'
    ];

    public function __construct(){
        $this->_cuotaRepository = new InstallmentRepository();
        $this->_pagoRepository = new PaymentRepository();
    }

    public function mount(){
        self::initialState();
    }

    public function initialState(){
        $this->reset(['cuota_id', 'monto_pendiente_cuota', 'monto_pagar', 'modo_pago', 'nombre_banco', 'numero_operacion']);
        $this->reset(['historial']);
    }

    public function render(){
        toastAlert($this, 'CARGANDO RENDER PAGOS','warning' );
        $cuota_matricula = null;
        $cuotas_ciclo = null;
        if($this->matricula_id){
            $cuota_matricula = $this->_cuotaRepository->cuotasActivasMatricula($this->matricula_id);
            $cuotas_ciclo = $this->_cuotaRepository->cuotasActivasCiclo($this->matricula_id);

            $informacionPagos = $this->_cuotaRepository->informacionMontosYDeudas($this->matricula_id);
            $this->general_deuda = $informacionPagos->general_deuda;
            $this->general_pagado = $informacionPagos->general_pagado;
            $this->matricula_deuda = $informacionPagos->matricula_deuda;
            $this->matricula_pagado = $informacionPagos->matricula_pagado;
            $this->ciclo_deuda = $informacionPagos->ciclo_deuda;
            $this->ciclo_pagado = $informacionPagos->ciclo_pagado;
            // informacion deuda pendiente
        }
        return view('livewire.matricula.partials.pago') ->with('cuota_matricula', $cuota_matricula)
                                                        ->with('cuotas_ciclo', $cuotas_ciclo);
    }

    /***********************************************************  CRUD *************************************************************/
    public function pagar(){

        if($this->modo_pago == ModoPagoEnum::EFECTIVO){
            $this->validateOnly('matricula_id');
            $this->validateOnly('cuota_id');
            $this->validateOnly('monto_pendiente_cuota');
            $this->validateOnly('monto_pagar');
            $this->validateOnly('modo_pago');
        }
        else
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

    public function anularPago( int $pago_id ){
        try {
            $this->_pagoRepository->anularPago($pago_id);
            sweetAlert($this, 'pago', EstadosEntidadEnum::DELETED);
            openModal($this,'#form-modal-historial', false);
        } catch (Exception $err) { toastAlert($this, $err->getMessage()); }
    }

    /***********************************************************  Funciones listeners *************************************************************/
    public function abrirModalPagos( string $tipoCuota = 'ciclo', $couta_id = null ) {
        self::initialState();
        switch ($tipoCuota) {
            case 'matricula':
                $cuota = $this->_cuotaRepository::find($couta_id);
                $this->cuota_id = $cuota->id;
                $this->monto_pendiente_cuota = $this->matricula_deuda - $this->matricula_pagado;
                $this->monto_pagar = $cuota->amount;
                break;
            case 'ciclo':
                $this->monto_pendiente_cuota = $this->ciclo_deuda -  $this->ciclo_pagado;
                $this->monto_pagar = 0;
                break;
            default: toastAlert($this, 'No se puede identificar el tipo de cuota a pagar', EstadosAlertasEnum::WARNING); break;
        }
        openModal($this,"#form-modal-pago");
    }

    public function abrirModalHistorial( int $cuota_id ) {
        $this->historial = $this->_cuotaRepository::find($cuota_id);
        openModal($this,'#form-modal-historial');
    }

    public function cargarDataPagos( int $matricula_id ){
        self::initialState();
        $this->matricula_id = $matricula_id;
    }

    /***********************************************************  Funciones internas *************************************************************/
    private function buildModelPago(){
        $modeloPago = $this->_pagoRepository->builderModelRepository();
        $modeloPago->matricula_id = $this->matricula_id;

        $modeloPago->cuota_id = $this->cuota_id;
        $modeloPago->montoPagado = $this->monto_pagar;
        $modeloPago->modo_pago = $this->modo_pago;
        $modeloPago->nombre_banco = $this->modo_pago == ModoPagoEnum::DEPOSITO_BANCARIO? $this->nombre_banco : null;
        $modeloPago->numero_operacion = $this->modo_pago == ModoPagoEnum::DEPOSITO_BANCARIO? $this->numero_operacion : null;
        return $modeloPago;
    }
}
