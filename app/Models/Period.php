<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    protected $table = 'periods';
    protected $primaryKey = 'id';

    public function levels()
    {
        return $this->hasMany(Level::class);
    }

    public function classrooms()
    {
        return $this->hasManyThrough(Classroom::class, Level::class);
    }

    public function enrollments()
    {
        return $this->hasManyThrough(Enrollment::class, Classroom::class);
    }

    protected $fillable = [
        'id',
        'name',
        'year',
        'active',
    ];
}
