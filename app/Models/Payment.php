<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;

    protected $table = 'payments';
    protected $primaryKey = 'id';

    protected $fillable = [
        'installment_id',
        'amount',
        'type',
        'concept_type',
        'user_id',
        'payment_id',
        'serie',
        'numeration',
    ];



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
