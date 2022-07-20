<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $table = 'schools';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'address',
        'district_id',
        'country_id',
    ];

    public function district()
    {
        return $this->belongsTo(District::class);
    }
}
