<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Relative extends Model
{
    public function entity()
    {
        return $this->belongsTo(Entity::class);
    }
}
