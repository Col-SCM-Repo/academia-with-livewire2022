<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseScore extends Model
{
    protected $table = "course_scores";
    protected $primariKey = "id";

    protected $fillable = [
        'exam_id',
        'course_id',
        'score_correct',
        'score_wrong',
        'number_questions',
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
