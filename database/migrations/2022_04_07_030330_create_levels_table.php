<?php

use App\Enums\EstadosEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLevelsTable extends Migration
{
	public function up()
	{
		Schema::create('levels', function (Blueprint $table) {
			$table->bigInteger('id', true);
			$table->bigInteger('type_id')->index('FK_levels_type_codes');
			$table->bigInteger('period_id')->index('FK_levels_periods');
			$table->date('start_date')->nullable();
			$table->date('end_date')->nullable();
			$table->decimal('price', 10)->nullable();
			$table->bigInteger('status')->default(EstadosEnum::ACTIVO);
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('levels');
	}
}
