<?php

namespace App\Repository;

use App\Enums\EstadosEnum;
use App\Enums\TiposConceptoPagoEnun;
use App\Enums\TiposPagoFacturaEnum;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use App\Models\Secuence;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class PaymentRepository extends Payment
{
    /*
        COLUMNAS: 
                    id	installment_id	amount	type	concept_type	
                    user_id	payment_id	serie	numeration
    */

    private $_cuotasRepository;

    public function __construct()
    {
        $this->_cuotasRepository = new InstallmentRepository();
    }

    public function builderModelRepository()
    {
        return (object) [
            'id' => null,
            'cuota_id' => null,
            'monto' => null,
            'autoFraccionamiento' => true
            // 'payment_id' => null,
            // 'serie' => null,
            // 'numeration' => null,    // numeracion ya no se va a utilizar 
        ];
    }

    public function registrarPago(object $modelBuilder)
    {
        $cuota = $this->_cuotasRepository::find($modelBuilder->cuota_id);
        if (!$cuota) throw new NotFoundResourceException('No se encontro la matricula especificada');

        $pago = null;

        if (!$modelBuilder->autoFraccionamiento) {
            if (round($modelBuilder->monto, 2) == round($cuota->amount, 2)) {
                $pago = new Payment();
                $pago->installment_id = $modelBuilder->cuota_id;
                $pago->amount = $modelBuilder->monto;
                $pago->type = TiposPagoFacturaEnum::TICKET;
                $pago->concept_type =  TiposConceptoPagoEnun::ENTERO;
                $pago->user_id = Auth::user()->id;
                $pago->save();
            } else {
                Log::debug('error de fraccionamiento (no coincide el pago con la cuota) [Payment repository::registrarPago]');
                return null;
            }
        } else {
            // Ahora se viene lo chidori 
            /*
            $pago = new Payment();
            $pago->installment_id = $modelBuilder->cuota_id;
            $pago->amount = $modelBuilder->monto;
            $pago->type = $modelBuilder->YYYYYYY;
            $pago->concept_type = $modelBuilder->YYYYYYY;
            $pago->user_id = $modelBuilder->YYYYYYY;
            $pago->payment_id = $modelBuilder->YYYYYYY;
            $pago->serie = $modelBuilder->YYYYYYY;
            $pago->numeration = $modelBuilder->YYYYYYY;
        */
        }
    }

    public function to_pay_installment($objPago)
    {
    }
}
