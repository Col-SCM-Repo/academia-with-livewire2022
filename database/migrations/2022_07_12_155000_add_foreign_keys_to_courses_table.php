<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToCoursesTable extends Migration
{
	public function up()
	{
		Schema::table('courses', function (Blueprint $table) {
			// $table->foreign('academic_area_id', 'FK_courses_academicArea')->references('id')->on('academic_areas');
			$table->foreign('user_id', 'FK_courses_user')->references('id')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}

	public function down() {
		Schema::table('courses', function (Blueprint $table) {
			// $table->dropForeign('FK_courses_academicArea');
			$table->dropForeign('FK_courses_user');
		});
	}
}
