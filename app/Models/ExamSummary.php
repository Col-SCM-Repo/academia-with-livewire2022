<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamSummary extends Model
{
    protected $table = "exam_summaries";
    protected $primariKey = "id";

    protected $fillable = [
        'code_exam',
        'student_code',
        'exam_id',
        'student_id',
        'student_type',
        'score_correct',
        'score_wrong',
        'final_score',
        'observation',
        'user_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function exam(){
        return $this->belongsTo(Exam::class, 'exam_id', 'id');
    }

    public function enrollment(){
        return $this->belongsTo(Enrollment::class, 'enrollment_id', 'id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function course_summary(){
        return $this->hasMany( CourseSummary::class, 'exam_summary_id', 'id');
    }
}
