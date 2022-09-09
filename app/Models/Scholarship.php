<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Scholarship extends Model
{
    use SoftDeletes;

    protected $table = 'scholarships';
    protected $primaryKey = 'id';

    protected $fillable = [
        'enrollment_id',
        'type_scholarship_id',
        'user_id',
        'description',
        'discount'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class, 'enrollment_id', 'id');
    }

    public function typeScholarship()
    {
        return $this->belongsTo(typeScholarship::class, 'type_scholarship_id', 'id');
    }

}
