<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('students', function (Blueprint $table) {
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('entity_id')->unsigned()->index('students_entity_id_foreign');
			$table->bigInteger('school_id')->unsigned()->index('FK_students_schools');
			$table->integer('graduation_year');
			$table->string('photo_file')->default('student-default.jpg');
			$table->softDeletes();
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('students');
	}
}
