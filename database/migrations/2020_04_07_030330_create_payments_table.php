<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payments', function (Blueprint $table) {
			$table->bigInteger('id', true);
			$table->bigInteger('installment_id')->nullable()->index('FK_payments_installments');
			$table->decimal('amount', 10)->nullable();
			$table->enum('type', array('ticket', 'note'));
			$table->enum('concept_type', array('none', 'partial', 'whole'));
			$table->bigInteger('user_id')->unsigned()->index('FK_payments_users');
			$table->bigInteger('payment_id')->nullable()->index('FK_payments_payments');
			$table->string('serie', 50)->nullable();
			$table->string('numeration', 50)->nullable();
			$table->softDeletes();
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
		Schema::drop('payments');
	}
}
