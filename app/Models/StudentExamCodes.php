<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Tabla temporal para examenes (se elimina en cada generacion de codigos )
class StudentExamCodes extends Model
{
    protected $table = "student_exam_codes";
    protected $primariKey = "id";
    public $timestamps  = false;

    protected $fillable = [
        'enrollment_id',
        'enrollment_code',      // Ultimos 4 digitos de la cartilla del examen.
        'code_exam', 	        // Primeros 2 digitos de la cartilla del examen.
        'level',
        'classroom',
        'surname',
        'name',
        'observation',
    ];

    public function enrollment(){
        return $this->belongsTo(Enrollment::class, 'enrollment_id', 'id');
    }

}
