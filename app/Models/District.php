<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $table = "districts";
    protected $primariKey = "id";

    protected $fillable = [
        'id',
        'name',
        'province_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function records()
    {
        return $this->hasMany('App\Record');
    }

    public function entities()
    {
        return $this->hasMany(Entity::class);
    }
}
