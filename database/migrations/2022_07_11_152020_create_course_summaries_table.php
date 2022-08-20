<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseSummariesTable extends Migration
{
    // Detalle sobre las calificaciones del examen
    public function up()
    {
        Schema::create('course_summaries', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
			$table->bigInteger('exam_summary_id')->unsigned()->index('FK_courseSummary_examSummary');
			$table->bigInteger('course_id')->unsigned()->index('FK_courseSummary_course');

            $table->integer('correct_answers')->unsigned()->default(0);
            $table->integer('wrong_answers')->unsigned()->default(0);
            $table->integer('blank_answers')->unsigned()->default(0);

            $table->double('course_score')->unsigned()->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('course_summaries');
    }
}
