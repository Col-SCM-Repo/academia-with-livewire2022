<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    //

    public function installment()
    {
        return $this->belongsTo(Installment::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function notes()
    {
        return $this->hasMany(Payment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
