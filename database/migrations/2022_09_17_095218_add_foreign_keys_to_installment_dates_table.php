<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToInstallmentDatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
	public function up()
	{
		Schema::table('instalment_dates', function (Blueprint $table) {
			$table->foreign('level_id', 'FK_instalmentDates_levels')->references('id')->on('levels')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('instalment_dates', function (Blueprint $table) {
			$table->dropForeign('FK_instalmentDates_levels');
		});
	}
}
