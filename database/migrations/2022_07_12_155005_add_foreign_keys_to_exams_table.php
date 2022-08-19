<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToExamsTable extends Migration
{
	public function up()
	{
		Schema::table('exams', function (Blueprint $table) {
			$table->foreign('period_id', 'FK_exams_period')->references('id')->on('periods')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('level_id', 'FK_exams_level')->references('id')->on('levels')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('group_id', 'FK_exams_group')->references('id')->on('groups')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('user_id', 'FK_exams_user')->references('id')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}

	public function down()
	{
		Schema::table('exams', function (Blueprint $table) {
			$table->dropForeign('FK_exams_period');
			$table->dropForeign('FK_exams_level');
			$table->dropForeign('FK_exams_group');
			$table->dropForeign('FK_exams_user');
		});
	}
}
