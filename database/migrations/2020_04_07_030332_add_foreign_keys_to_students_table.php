<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToStudentsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('students', function (Blueprint $table) {
			$table->foreign('entity_id', 'FK_students_entities')->references('id')->on('entities')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('school_id', 'FK_students_schools')->references('id')->on('schools')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('students', function (Blueprint $table) {
			$table->dropForeign('FK_students_entities');
			$table->dropForeign('FK_students_schools');
		});
	}
}
