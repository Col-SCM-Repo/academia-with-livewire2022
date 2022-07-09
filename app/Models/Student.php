<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    //

    public function entity()
    {
        return $this->belongsTo('App\Entity');
    }

    public function school()
    {
        return $this->belongsTo('App\School');
    }

    public function enrollment()
    {
        return $this->hasOne('App\Enrollment');
    }
}
