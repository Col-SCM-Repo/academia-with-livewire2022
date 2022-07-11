<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Career extends Model
{

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }
}
