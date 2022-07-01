<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Incidente extends Model
{
    use SoftDeletes;

    protected $table = 'incidentes';
    protected $primaryKey = 'id';

    public $fillable = [
        'id',
        'tipo_incidente',
        'descripcion',
        'justificacion',
        'parentesco',
        'fecha_reporte',
        'created_at',
    ];

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class, 'enrollment_id', 'id');
    }

    public function auxiliar()
    {
        return $this->hasOne(User::class, 'id', 'auxiliar_id');
    }

    public function secretaria()
    {
        return $this->hasOne(User::class, 'id', 'secretaria_id');
    }

    public function evidencias()
    {
        return $this->hasMany(Evidencia::class, 'incidente_id', 'id');
    }

    public function estado()
    {
        switch ($this->estado) {
            case '-1':
                return "eliminado";
            case '0':
                return "pendiente";
            case '1':
                return "justificado";
        }
        return "undefined";
    }
}
