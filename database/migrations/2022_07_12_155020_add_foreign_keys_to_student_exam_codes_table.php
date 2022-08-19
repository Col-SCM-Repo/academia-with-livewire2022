<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToStudentExamCodesTable extends Migration
{
	public function up()
	{
		Schema::table('student_exam_codes', function (Blueprint $table) {
			$table->foreign('student_id', 'FK_examsCode_student')->references('id')->on('students')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}

	public function down()
	{
		Schema::table('student_exam_codes', function (Blueprint $table) {
			$table->dropForeign('FK_examsCode_student');
		});
	}
}
