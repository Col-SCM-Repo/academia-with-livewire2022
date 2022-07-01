<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Installment extends Model
{
    //
    protected $table = 'installments';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function payments()
    {
        return $this->hasMany('App\Payment')->where('type', '=', 'ticket');
    }

    public function note_payments()
    {
        return $this->hasMany('App\Payment')->where('type', '=', 'note');
    }

    public function enrollment()
    {
        return $this->belongsTo('App\Enrollment');
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
