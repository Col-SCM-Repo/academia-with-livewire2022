<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToEnrollmentsTable extends Migration
{
	public function up()
	{
		Schema::table('enrollments', function (Blueprint $table) {
			$table->foreign('classroom_id', 'FK_enrollments_classrooms')->references('id')->on('classrooms')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('relative_id', 'FK_enrollments_relatives')->references('id')->on('relatives')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('student_id', 'FK_enrollments_students')->references('id')->on('students')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('user_id', 'FK_enrollments_users')->references('id')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('career_id', 'FK_enrollments_carreer')->references('id')->on('careers')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}

	public function down()
	{
		Schema::table('enrollments', function (Blueprint $table) {
			$table->dropForeign('FK_enrollments_classrooms');
			$table->dropForeign('FK_enrollments_relatives');
			$table->dropForeign('FK_enrollments_students');
			$table->dropForeign('FK_enrollments_users');
			$table->dropForeign('FK_enrollments_carreer');
		});
	}
}
