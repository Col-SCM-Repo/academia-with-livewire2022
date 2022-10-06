<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamSummariesTable extends Migration
{
    // Resultados del examen
    public function up()
    {
        Schema::create('exam_summaries', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();

			$table->bigInteger('exam_id')->unsigned()->index('FK_examSummaries_exam');
			$table->bigInteger('enrollment_id')->unsigned()->nullable()->index('FK_examSummaries_enrollment');
			$table->string('code_exam')->nullable();        // primeros dos digitos del examen
			$table->string('code_enrollment')->nullable();  // Ultimos 4 digitos del codigo de alumno

			$table->enum('student_type', array('free', 'student'))->default('student');

            $table->smallInteger('correct_answer')->unsigned()->default(0);
            $table->smallInteger('wrong_answer')->unsigned()->default(0);
            $table->smallInteger('blank_answer')->unsigned()->default(0);
            $table->double('score_correct')->default(0);
            $table->double('score_wrong')->default(0);
            $table->double('final_score')->default(0);

			$table->string('surname', 100);
			$table->string('name', 100);

            $table->text('observation')->nullable();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('exam_summaries');
    }
}
