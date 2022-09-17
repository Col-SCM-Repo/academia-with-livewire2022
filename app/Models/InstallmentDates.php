<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Installment extends Model
{
    protected $table = 'instalment_dates';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'enrollment_id',
        'order',
        'type',
        'amount',
        'state',
    ];

}
