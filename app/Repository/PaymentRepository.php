<?php

namespace App\Repository;

use App\Enums\EstadosEnum;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use App\Models\Secuence;
use Illuminate\Support\Facades\DB;

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
        $modelRepository = [
            'id' => 0,
            'installmet_id' => null,
            'amount' => null,
            'type' => 'ticket',
            'concept_type' => 'partial',
            'user_id' => Auth::user()->id,
            'payment_id' => null,
            'serie' => null,
            'numeration' => null,
        ];
        return $modelRepository;
    }

    public function registrarPago($matricula_id)
    {
        $cuotas_pago = $this->_cuotasRepository::where('enrollment_id', $matricula_id)
            ->where('status', EstadosEnum::ACTIVO);
    }

    public function to_pay_installment($objPago)
    {
    }
}
