<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    //

    public function entity()
    {
        return $this->belongsTo(Entity::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function enrollment()
    {
        return $this->hasOne(Enrollment::class);
    }
}
