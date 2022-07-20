<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $table = 'groups';
    protected $primaryKey = 'id';

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
