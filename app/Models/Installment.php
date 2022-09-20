<?php

namespace App\Models;

use App\Enums\TiposPagoFacturaEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Installment extends Model
{
    //
    use SoftDeletes;

    protected $table = 'installments';
    protected $primaryKey = 'id';

    protected $fillable = [
        'enrollment_id',
        'order',
        'type',
        'amount',
        'state',
    ];

    /*     public function payments()
    {
        return $this->hasMany(Payment::class)->where('type', '=', 'ticket');
    } */
    public function payments()
    {
        return $this->hasMany(Payment::class, 'installment_id', 'id')
                ->where('type', '=', TiposPagoFacturaEnum::TICKET)->orderBy('id', 'asc');
    }

    public function abonado()
    {
        $abonadoAcumulador = 0;
        foreach ($this->payments as $pago) {
            if(!$pago->note){
                $abonadoAcumulador+= $pago->amount;
            }
        }
        return  $abonadoAcumulador;
    }

    /*
    public function allPayments()
    {
        return $this->hasMany(Payment::class, 'installment_id', 'id');
    }

    public function note_payments()
    {
        return $this->hasMany(Payment::class)->where('type', '=', 'note');
    }
    */

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function balance()
    {
        $tickets_sum = $this->payments->sum('amount');
        $notes_sum = $this->note_payments->sum('amount');

        return ($tickets_sum - $notes_sum);
    }

    public function status()
    {
        $balance = $this->balance();
        // $total_period_cost=$this->enrollment->period_cost;
        if ($balance >= $this->amount) {
            return 'paid_out';
        } else {
            return 'pending';
        }
    }
}
