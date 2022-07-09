<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    //

    public function installment()
    {
        return $this->belongsTo('App\Installment');
    }

    public function payment()
    {
        return $this->belongsTo('App\Payment');
    }

    public function notes()
    {
        return $this->hasMany('App\Payment');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
