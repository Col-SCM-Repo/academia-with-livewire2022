<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = [
        'description'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function careers()
    {
        return $this->hasMany(Career::class);
    }
}
