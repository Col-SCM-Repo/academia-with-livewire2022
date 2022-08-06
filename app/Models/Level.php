<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    //
    protected $table = 'levels';
    protected $primaryKey = 'id';

    protected $fillable = [
        'type_id',
        'period_id',
        'start_date',
        'end_date',
        'price',
    ];

    public function period()
    {
        return $this->belongsTo(Period::class, 'period_id', 'id');
    }

    public function classrooms()
    {
        return $this->hasMany(Classroom::class, 'level_id', 'id');
    }

    public function level_type()
    {
        return $this->belongsTo(Type_code::class, 'type_id', 'id');
    }

    public function enrollments()
    {
        return $this->hasManyThrough(Enrollment::class, Classroom::class);
    }
}
