<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamQuestionsTable extends Migration
{
    // Respuestas correctas del examen (Solucionario)
    public function up()
    {
        Schema::create('exam_questions', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
			$table->bigInteger('exam_id')->unsigned()->nullable()->index('FK_examQuestions_exam');
			$table->bigInteger('course_id')->unsigned()->nullable()->index('FK_examQuestions_course');

			$table->integer('question_number')->unsigned();
			$table->double('score')->unsigned();
			$table->enum('correct_answer', array( 'A', 'B', 'C','D', 'E'));

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('exam_questions');
    }
}
