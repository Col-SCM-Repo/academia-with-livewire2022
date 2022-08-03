<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchoolsTable extends Migration
{
	public function up()
	{
		Schema::create('schools', function (Blueprint $table) {
			$table->bigInteger('id', true)->unsigned();
			$table->string('name', 100);
			$table->text('address', 65535)->nullable();
			$table->bigInteger('district_id')->unsigned()->index('FK_schools_districts')->nullable();
			$table->bigInteger('country_id')->unsigned()->index('FK_schools_countries')->nullable();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('schools');
	}
}
