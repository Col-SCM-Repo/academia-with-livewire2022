<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToClassroomsTable extends Migration
{
	public function up()
	{
		Schema::table('classrooms', function (Blueprint $table) {
			$table->foreign('level_id', 'FK_classrooms_levels')->references('id')->on('levels')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}

	public function down()
	{
		Schema::table('classrooms', function (Blueprint $table) {
			$table->dropForeign('FK_classrooms_levels');
		});
	}
}
