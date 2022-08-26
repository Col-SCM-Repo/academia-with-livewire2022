<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $table = "courses";
    protected $primariKey = "id";

    protected $fillable = [
        'name',
        'status',
        'academic_area_id',
        'user_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
