<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entity extends Model
{
    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function relative()
    {
        return $this->hasOne(Relative::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function full_name()
    {
        return $this->father_lastname . ' ' . $this->mother_lastname . ', ' . $this->name;
    }
}
