<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class CreateClassroomsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('classrooms', function (Blueprint $table) {
			$table->bigInteger('id', true)->unsigned();
			$table->text('name', 65535);
			$table->bigInteger('level_id')->index('FK_classrooms_levels');
			$table->integer('vacancy');
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
		Schema::drop('classrooms');
	}
}
