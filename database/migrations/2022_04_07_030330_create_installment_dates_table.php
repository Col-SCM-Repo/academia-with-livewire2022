<?php

use App\Enums\EstadosEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstallmentDatesTable extends Migration
{
    // Si son dos cuotas, la primera se paga al instante, por lo que solo quedaria pendiente 1 cuota
    // numero de cuotas pedientes = numero cuotas -1
	public function up()
	{
		Schema::create('instalment_dates', function (Blueprint $table) {
			$table->bigInteger('id', true);
			$table->bigInteger('level_id')->index('FK_instalmentDates_levels');

			$table->integer('order')->nullable();
			$table->date('expiration_date')->nullable();
			$table->text('observations')->nullable();
		});
	}

	public function down()
	{
		Schema::drop('instalment_dates');
	}
}
