<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Tabla temporal para examenes (se elimina en cada generacion de codigos )
class StudentExamCodes extends Model
{
    protected $table = "student_exam_codes";
    protected $primariKey = "id";

    protected $fillable = [
        'enrollment_id',
        'type_code_id',
        'enrollment_code',      // Ultimos 4 digitos de la cartilla del examen.
        'code_exam', 	        // Primeros 2 digitos de la cartilla del examen.
        'observation',
        'surname',
        'name',
        'observation',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];


    public function enrollment(){
        return $this->belongsTo(Enrollment::class, 'enrollment_id', 'id');
    }

    public function type_code(){
        return $this->belongsTo(type_code::class, 'type_code_id', 'id');
    }


}
