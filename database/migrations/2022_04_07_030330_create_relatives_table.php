<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRelativesTable extends Migration
{
	public function up()
	{
		Schema::create('relatives', function (Blueprint $table) {
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('entity_id')->unsigned()->index('FK_relatives_entities');
			$table->bigInteger('occupation_id')->unsigned()->index('FK_relatives_occupation');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('relatives');
	}
}
