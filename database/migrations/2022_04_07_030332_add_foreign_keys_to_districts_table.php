<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToDistrictsTable extends Migration
{
	public function up()
	{
		Schema::table('districts', function (Blueprint $table) {
			$table->foreign('province_id', 'FK_districts_provinces')->references('id')->on('provinces')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}

	public function down()
	{
		Schema::table('districts', function (Blueprint $table) {
			$table->dropForeign('FK_districts_provinces');
		});
	}
}
