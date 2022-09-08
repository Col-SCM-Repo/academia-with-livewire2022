<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TypeScholarship extends Model
{
    protected $table = 'type_scholarships';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'description',
        'type',
        'value',
    ];
}
