<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRelativesTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('relatives', function (Blueprint $table) {
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('entity_id')->unsigned()->index('relatives_entity_id_foreign');
			$table->bigInteger('occupation_id');
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
		Schema::drop('relatives');
	}
}
