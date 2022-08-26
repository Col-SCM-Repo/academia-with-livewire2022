<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $table = "exams";
    protected $primariKey = "id";

    protected $fillable = [
        'period_id',
        'level_id',
        'group_id',
        'group_code',
        'name',
        'number_questions',
        'evaluation_type',
        'exam_date',
        'user_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function period(){
        return $this->belongsTo( Period::class , 'period_id', 'id');
    }

    public function level(){
        return $this->belongsTo( Level::class , 'level_id', 'id');
    }

    public function group(){
        return $this->belongsTo( Group::class, 'group_id', 'id');
    }

    public function user(){
        return $this->belongsTo( User::class , 'user_id', 'id');
    }

}
