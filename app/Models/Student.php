<?php

namespace App\Models;

use App\Enums\EstadosEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use SoftDeletes;

    protected $table = 'students';
    protected $primaryKey = 'id';

    protected $fillable = [
        'entity_id',
        'school_id',
        'graduation_year',
        'photo_file',
    ];

    public function entity()
    {
        return $this->belongsTo(Entity::class, 'entity_id', 'id');
    }

    public function relative()
    {
        return $this->hasMany(Relative::class, 'student_id', 'id');
    }

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id', 'id');
    }

    public function enrollment()
    {
        // Ultima matricula
        return $this->hasOne(Enrollment::class, 'student_id', 'id')->where('enrollments.status', EstadosEnum::ACTIVO)->orderBy('id', 'desc')->first();
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'student_id', 'id');
    }
}
