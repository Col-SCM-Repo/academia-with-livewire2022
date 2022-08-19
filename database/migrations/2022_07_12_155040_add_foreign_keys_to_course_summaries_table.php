<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToCourseSummariesTable extends Migration
{
	public function up()
	{
		Schema::table('course_summaries', function (Blueprint $table) {
			$table->foreign('exam_summary_id', 'FK_courseSummary_examSummary')->references('id')->on('exam_summaries')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('course_id', 'FK_courseSummary_course')->references('id')->on('courses')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}

	public function down()
	{
		Schema::table('course_summaries', function (Blueprint $table) {
			$table->dropForeign('FK_courseSummary_examSummary');
			$table->dropForeign('FK_courseSummary_course');
		});
	}
}
