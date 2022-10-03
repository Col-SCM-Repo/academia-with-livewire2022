<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToExamSummariesTable extends Migration
{
	public function up()
	{
		Schema::table('exam_summaries', function (Blueprint $table) {
			$table->foreign('exam_id', 'FK_examSummaries_exam')->references('id')->on('exams')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('enrollment_id', 'FK_examSummaries_enrollment')->references('id')->on('students')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}

	public function down()
	{
		Schema::table('exam_summaries', function (Blueprint $table) {
			$table->dropForeign('FK_examSummaries_exam');
			$table->dropForeign('FK_examSummaries_enrollment');
		});
	}
}
