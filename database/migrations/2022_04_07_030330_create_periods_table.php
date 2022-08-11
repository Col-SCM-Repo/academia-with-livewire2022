<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeriodsTable extends Migration
{
	public function up()
	{
		Schema::create('periods', function (Blueprint $table) {
			$table->bigInteger('id', true);
			$table->text('name', 65535);
			$table->integer('year');
			$table->integer('active')->default(0);
			$table->timestamps();
			$table->softDeletes();
		});
	}

	public function down()
	{
		Schema::drop('periods');
	}
}
