<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entity extends Model
{
    //

    public function district()
    {
        return $this->belongsTo('App\District');
    }




    public function student()
    {
        return $this->hasOne('App\Student');
    }

    public function relative()
    {
        return $this->hasOne('App\Relative');
    }

    public function user()
    {
        return $this->hasOne('App\User');
    }

    public function full_name()
    {
        return $this->father_lastname . ' ' . $this->mother_lastname . ', ' . $this->name;
    }
}
