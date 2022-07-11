<?php

namespace App\EJB;

use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use App\Models\Secuence;
use Illuminate\Support\Facades\DB;

class PaymentEJB extends Payment
{
    public function builderModelEJB()
    {
        $modelEJB = [
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
        return $modelEJB;
    }

    public function to_pay_installment($objPago)
    {

        DB::beginTransaction();

        try {
            $payment = new Payment();

            if ($objPago->type == 'note' && isset($objPago->payment_id)) {
                $payment->payment_id = $objPago->payment_id;
            }

            $payment->installment_id = $objPago->installment_id;
            $payment->amount = $objPago->amount;
            $payment->type = $objPago->type;
            $payment->concept_type = $objPago->concept_type;
            $payment->user_id = Auth::user()->id;

            // DB::raw('LOCK TABLES secuences WRITE;');

            $result = Secuence::where('doc_type', $objPago->type)->where('status', 1); //->lockForUpdate()->get();

            // if($result->isNotEmpty()){
            if ($result->exists()) {
                // $secuence=$result->first();
                $secuence = $result->lockForUpdate()->first();
                $numeracion = str_pad($secuence->current, $secuence->length, "0", STR_PAD_LEFT);

                $current = (int)($secuence->current);
                $secuence->current = ($current + 1);
                $secuence->save();

                $payment->serie = $secuence->serie;
                $payment->numeration = $numeracion;
            }
            // DB::raw('UNLOCK TABLES;');            
            $payment->save();

            DB::commit();

            return ['success' => true, 'installment_id' => $objPago->installment_id, "payment_id" => $payment->id];
        } catch (\Exception $e) {
            DB::rollback();
            return ['success' => false];
        }
    }
}
