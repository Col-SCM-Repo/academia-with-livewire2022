<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTypeCodesTable extends Migration
{
	public function up()
	{
		Schema::create('type_codes', function (Blueprint $table) {
			$table->bigInteger('id', true);
			$table->text('description', 65535)->nullable();
			$table->text('type', 65535)->nullable();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('type_codes');
	}
}
