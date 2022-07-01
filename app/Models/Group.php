<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{

    public function careers()
    {
        return $this->hasMany('App\Career');
    }
}
