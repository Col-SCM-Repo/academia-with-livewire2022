<?php

use App\Enums\ModoPagoEnum;
use App\Enums\TiposConceptoPagoEnun;
use App\Enums\TiposPagoFacturaEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
	public function up()
	{
		Schema::create('payments', function (Blueprint $table) {
			$table->bigInteger('id', true);
			$table->bigInteger('installment_id')->nullable()->index('FK_payments_installments');
			$table->decimal('amount', 10)->nullable();
			$table->enum('pay_mode', array(ModoPagoEnum::EFECTIVO, ModoPagoEnum::DEPOSITO_BANCARIO))->nullable();
			$table->enum('type', array(TiposPagoFacturaEnum::TICKET, TiposPagoFacturaEnum::DEVOLUCION));
			$table->enum('concept_type', array(TiposConceptoPagoEnun::NONE, TiposConceptoPagoEnun::ENTERO, TiposConceptoPagoEnun::PARCIAL));
			$table->bigInteger('user_id')->unsigned()->index('FK_payments_users');
			$table->bigInteger('payment_id')->nullable()->index('FK_payments_payments');
			$table->string('serie', 50)->nullable();
			$table->string('numeration', 50)->nullable();
			$table->string('bank_name', 50)->nullable();
			$table->string('operation_number', 50)->nullable();
			$table->text('path_voucher')->nullable();
			$table->text('observations')->nullable();
			$table->softDeletes();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('payments');
	}
}
