<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProvincesTable extends Migration
{
	public function up()
	{
		Schema::create('provinces', function (Blueprint $table) {
			$table->bigInteger('id', true)->unsigned();
			$table->string('name');
			$table->bigInteger('state_id')->unsigned()->index('FK_provinces_states');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('provinces');
	}
}
