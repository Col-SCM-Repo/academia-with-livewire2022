<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Relative extends Model
{
    protected $table = 'relatives';
    protected $primaryKey = 'id';

    protected $fillable = [
        'entity_id',
        'occupation_id',
    ];

    public function entity()
    {
        return $this->belongsTo(Entity::class, 'entity_id', 'id');
    }

    public function occupation()
    {
        return $this->belongsTo(occupation::class, 'occupation_id', 'id');
    }
}
