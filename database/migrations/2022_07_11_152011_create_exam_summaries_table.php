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
			$table->string('code_exam')->nullable();
			$table->string('student_code')->nullable();
			$table->bigInteger('exam_id')->unsigned()->nullable()->index('FK_examSummaries_exam');
			$table->bigInteger('student_id')->unsigned()->nullable()->index('FK_examSummaries_student');
			$table->enum('student_type', array('free', 'student'))->default('student');
            $table->double('puntaje_acumulado')->unsigned()->default(0);
            $table->double('puntaje_restado')->unsigned()->default(0);
            $table->double('puntaje_final')->unsigned()->default(0);

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
