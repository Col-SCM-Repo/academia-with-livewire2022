<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToCourseScoresTable extends Migration
{
	public function up()
	{
		Schema::table('course_scores', function (Blueprint $table) {
			$table->foreign('exam_id', 'FK_courseScore_exam')->references('id')->on('exams')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('course_id', 'FK_courseScore_course')->references('id')->on('courses')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}

	public function down()
	{
		Schema::table('course_scores', function (Blueprint $table) {
			$table->dropForeign('FK_courseScore_exam');
			$table->dropForeign('FK_courseScore_course');
		});
	}
}
