<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Career extends Model
{
    protected $table = "careers";
    protected $primariKey = "id";

    protected $fillable = [
        'group_id',
        'career',
        'nmonico',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }
}
