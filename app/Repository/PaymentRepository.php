<?php

namespace App\Repository;

use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use App\Models\Secuence;
use Illuminate\Support\Facades\DB;

class PaymentRepository extends Payment
{
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

    public function to_pay_installment($objPago)
    {
    }
}
