<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseSummary extends Model
{
    protected $table = "course_summaries";
    protected $primariKey = "id";

    protected $fillable = [
        'exam_summary_id',
        'course_id',
        'correct_answers',
        'wrong_answers',
        'blank_answers',
        'student_responses',
        'course_score',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function exam_summary(){
        return $this->belongsTo(ExamSummary::class, 'exam_summary_id', 'id');
    }

    public function course(){
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }

    /*
        NOTA:   * AGREGAR NUMERO DE PREGUNTAS
                * AGREGAR PUNTAJE DE PREGUNTAS
    */


}
