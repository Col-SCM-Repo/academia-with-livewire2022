<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchoolsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('schools', function (Blueprint $table) {
			$table->bigInteger('id', true)->unsigned();
			$table->string('name', 100);
			$table->text('address', 65535)->nullable();
			$table->bigInteger('district_id')->unsigned()->index('schools_district_id_foreign')->nullable();
			$table->bigInteger('country_id')->unsigned()->index('schools_country_id_foreign')->nullable();
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
		Schema::drop('schools');
	}
}
