<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToProvincesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('provinces', function(Blueprint $table)
		{
			$table->foreign('state_id', 'FK_provinces_states')->references('id')->on('states')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('provinces', function(Blueprint $table)
		{
			$table->dropForeign('FK_provinces_states');
		});
	}

}
