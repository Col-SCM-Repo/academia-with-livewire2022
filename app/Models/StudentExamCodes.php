<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Tabla temporal para examenes (se elimina en cada generacion de codigos )
class StudentExamCodes extends Model
{
    protected $table = "student_exam_codes";
    protected $primariKey = "id";

    protected $fillable = [
        'student_id',
        'student_code',     // Ultimos 4 digitos de la cartilla del examen.
        'code_exam', 	    // Primeros 2 digitos de la cartilla del examen.
        'observation',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];


    public function student(){
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

}
