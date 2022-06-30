<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSecuencesTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('secuences', function (Blueprint $table) {
			$table->integer('id', true);
			$table->enum('doc_type', array('ticket', 'note'))->nullable();
			$table->string('serie', 45)->nullable();
			$table->integer('length')->nullable();
			$table->integer('current')->nullable()->default(1);
			$table->integer('status')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('secuences');
	}
}
