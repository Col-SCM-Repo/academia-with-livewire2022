<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    protected $table = 'periods';
    protected $primaryKey = 'id';

    //

    public function levels()
    {
        return $this->hasMany('App\Level');
    }

    public function classrooms()
    {
        return $this->hasManyThrough('App\Classroom', 'App\Level');
    }

    public function enrollments()
    {
        return $this->hasManyThrough('App\Enrollment', 'App\Classroom');
    }


    protected $fillable = [
        'id',
        'name',
        'year',
        'active',
    ];
}
