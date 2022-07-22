<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToRelativesTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('relatives', function (Blueprint $table) {
			$table->foreign('entity_id', 'FK_relatives_entities')->references('id')->on('entities')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('occupation_id', 'FK_relatives_occupation')->references('id')->on('occupations')->onUpdate('RESTRICT')->onDelete('RESTRICT');
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
			$table->dropForeign('FK_relatives_entities');
			$table->dropForeign('FK_relatives_occupation');
		});
	}
}
