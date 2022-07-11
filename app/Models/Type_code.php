<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Type_code extends Model
{
    //

    public function ts_levels()
    {
        return $this->hasMany(Level::class, 'type_id');
    }
}
