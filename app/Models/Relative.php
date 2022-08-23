<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Relative extends Model
{
    protected $table = 'relatives';
    protected $primaryKey = 'id';

    protected $fillable = [
        'entity_id',
        'student_id',
        'relative_relationship',
        'occupation_id',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function apoderado()
    {
        return $this->belongsTo(Entity::class, 'entity_id', 'id');
    }

    public function occupation()
    {
        return $this->belongsTo(occupation::class, 'occupation_id', 'id');
    }
}
