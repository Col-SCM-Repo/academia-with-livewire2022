<?php

use App\Enums\EstadosEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstallmentsTable extends Migration
{
	public function up()
	{
		Schema::create('installments', function (Blueprint $table) {
			$table->bigInteger('id', true);
			$table->bigInteger('enrollment_id')->unsigned()->index('FK_installments_enrollments');
			$table->integer('order')->unsigned();
			$table->enum('type', array('enrollment', 'installment'));
			$table->decimal('amount', 10);
			$table->string('status')->default(EstadosEnum::ACTIVO);
			$table->softDeletes();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('installments');
	}
}
