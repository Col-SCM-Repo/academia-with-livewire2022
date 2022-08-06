<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Classroom extends Model
{
    use SoftDeletes;

    protected $table = "classrooms";
    protected $primariKey = "id";

    protected $fillable = [
        'name',
        'level_id',
        'vacancy',
    ];

    protected $hidden = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    public function level()
    {
        return $this->belongsTo(Level::class, 'level_id', 'id');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'classroom_id', 'id');
    }

    // public function full_description(){
    //     return $this->level->period->name.' - '.$this->level->period->name;
    // }
}
