<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    //

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    // public function full_description(){
    //     return $this->level->period->name.' - '.$this->level->period->name;
    // }
}
