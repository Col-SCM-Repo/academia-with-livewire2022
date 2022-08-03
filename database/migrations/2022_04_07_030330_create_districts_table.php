<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDistrictsTable extends Migration
{
	public function up()
	{
		Schema::create('districts', function (Blueprint $table) {
			$table->bigInteger('id', true)->unsigned();
			$table->string('name');
			$table->bigInteger('province_id')->unsigned()->index('FK_districts_provinces')->nullable();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('districts');
	}
}
