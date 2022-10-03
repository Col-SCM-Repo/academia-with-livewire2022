<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToStudentExamCodesTable extends Migration
{
	public function up()
	{
		Schema::table('student_exam_codes', function (Blueprint $table) {
			$table->foreign('enrollment_id', 'FK_examsCode_enrollment')->references('id')->on('enrollments')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('type_code_id', 'FK_examsCode_typeCodes')->references('id')->on('type_codes')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}

	public function down()
	{
		Schema::table('student_exam_codes', function (Blueprint $table) {
			$table->dropForeign('FK_examsCode_enrollment');
			$table->dropForeign('FK_examsCode_typeCodes');
		});
	}
}
