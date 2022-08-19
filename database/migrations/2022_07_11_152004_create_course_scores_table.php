<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CourseScores extends Migration
{
    public function up()
    {
        Schema::create('course_scores', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
            $table->bigInteger('exam_id')->unsigned()->index('FK_courseScore_exam');
            $table->bigInteger('course_id')->unsigned()->index('FK_courseScore_course');
            $table->integer('score')->unsigned();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('course_scores');
    }
}
