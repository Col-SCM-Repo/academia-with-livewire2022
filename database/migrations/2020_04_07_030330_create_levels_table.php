<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLevelsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('levels', function (Blueprint $table) {
			$table->bigInteger('id', true);
			$table->bigInteger('type_id')->index('FK_levels_type_codes');
			$table->bigInteger('period_id')->index('FK_levels_periods');
			$table->date('start_date');
			$table->date('end_date');
			$table->decimal('price', 10);
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
		Schema::drop('levels');
	}
}
