<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    //

    public function records()
    {
        return $this->hasMany('App\Record');
    }

    public function entities()
    {
        return $this->hasMany(Entity::class);
    }
}
