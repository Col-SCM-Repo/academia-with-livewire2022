<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    //

    public function period()
    {
        return $this->belongsTo('App\Period');
    }

    public function classrooms()
    {
        return $this->hasMany('App\Classroom');
    }

    public function level_type()
    {
        return $this->belongsTo('App\Type_code', 'type_id');
    }

    public function enrollments()
    {
        return $this->hasManyThrough('App\Enrollment', 'App\Classroom');
    }
}
