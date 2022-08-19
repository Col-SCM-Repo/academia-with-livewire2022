<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToExamQuestionsTable extends Migration
{
	public function up()
	{
		Schema::table('exam_questions', function (Blueprint $table) {
			$table->foreign('exam_id', 'FK_examQuestions_exam')->references('id')->on('exams')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('course_id', 'FK_examQuestions_course')->references('id')->on('courses')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}

	public function down()
	{
		Schema::table('exam_questions', function (Blueprint $table) {
			$table->dropForeign('FK_examQuestions_exam');
			$table->dropForeign('FK_examQuestions_course');
		});
	}
}
