<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToEntitiesTable extends Migration
{
	public function up()
	{
		Schema::table('entities', function (Blueprint $table) {
			$table->foreign('country_id', 'FK_entities_countries')->references('id')->on('countries')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('district_id', 'FK_entities_districts')->references('id')->on('districts')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}

	public function down()
	{
		Schema::table('entities', function (Blueprint $table) {
			$table->dropForeign('FK_entities_countries');
			$table->dropForeign('FK_entities_districts');
		});
	}
}
