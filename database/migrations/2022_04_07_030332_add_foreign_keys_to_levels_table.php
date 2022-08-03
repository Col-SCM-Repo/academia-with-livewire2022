<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToLevelsTable extends Migration
{
	public function up()
	{
		Schema::table('levels', function (Blueprint $table) {
			$table->foreign('period_id', 'FK_levels_periods')->references('id')->on('periods')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('type_id', 'FK_levels_type_codes')->references('id')->on('type_codes')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}

	public function down()
	{
		Schema::table('levels', function (Blueprint $table) {
			$table->dropForeign('FK_levels_periods');
			$table->dropForeign('FK_levels_type_codes');
		});
	}
}
