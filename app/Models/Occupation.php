<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Occupation extends Model
{
    protected $table = 'occupations';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
    ];
}
