<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToPaymentsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('payments', function (Blueprint $table) {
			$table->foreign('installment_id', 'FK_payments_installments')->references('id')->on('installments')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('payment_id', 'FK_payments_payments')->references('id')->on('payments')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('user_id', 'FK_payments_users')->references('id')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('payments', function (Blueprint $table) {
			$table->dropForeign('FK_payments_installments');
			$table->dropForeign('FK_payments_payments');
			$table->dropForeign('FK_payments_users');
		});
	}
}
