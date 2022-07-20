<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Relative extends Model
{
    protected $table = 'relatives';
    protected $primaryKey = 'id';

    public function entity()
    {
        return $this->belongsTo(Entity::class);
    }
}
