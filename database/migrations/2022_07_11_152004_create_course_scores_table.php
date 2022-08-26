<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseScoresTable extends Migration
{
    public function up()
    {
        Schema::create('course_scores', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
            $table->bigInteger('exam_id')->unsigned()->index('FK_courseScore_exam');
            $table->bigInteger('course_id')->unsigned()->index('FK_courseScore_course');
            $table->double('score_correct')->unsigned();            // Puntaje de las preguntas buenas
            $table->double('score_wrong')->unsigned()->default(1);  // Puntaje de las preguntas malas
            $table->integer('number_questions')->unsigned();        // Numero de preguntas
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('course_scores');
    }
}
