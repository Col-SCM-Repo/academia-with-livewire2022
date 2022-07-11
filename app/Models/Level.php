<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    //

    public function period()
    {
        return $this->belongsTo(Period::class);
    }

    public function classrooms()
    {
        return $this->hasMany(Classroom::class);
    }

    public function level_type()
    {
        return $this->belongsTo(Type_code::class, 'type_id');
    }

    public function enrollments()
    {
        return $this->hasManyThrough(Enrollment::class, Classroom::class);
    }
}
