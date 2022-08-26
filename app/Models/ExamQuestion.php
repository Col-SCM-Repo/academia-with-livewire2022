<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamQuestion extends Model
{
    protected $table = "exam_questions";
    protected $primariKey = "id";

    protected $fillable = [
        'exam_id',
        'course_id',
        'question_number',
        'score',
        'correct_answer'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function course(){
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }

    public function exam(){
        return $this->belongsTo(Exam::class, 'exam_id', 'id');
    }

}
