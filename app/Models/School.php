<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    //

    public function district()
    {
        return $this->belongsTo('App\District');
    }
}
