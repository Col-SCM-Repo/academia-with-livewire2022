<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Evidencia extends Model
{
    use SoftDeletes;

    protected $table = 'evidencias';
    protected $primaryKey = 'id';

    public $fillable = [
        'id',
        'evidencia_descripcion',
        'path',
        'estado',
        'user_id',
        'created_at',
    ];

    public function incidente()
    {
        return $this->belongsTo(Incidente::class, 'incidente_id', 'id');
    }

    public function usuario()
    {
        return $this->hasOne(User::class, 'user_id', 'id');
    }
}
