<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToInstallmentsTable extends Migration
{
	public function up()
	{
		Schema::table('installments', function (Blueprint $table) {
			$table->foreign('enrollment_id', 'FK_installments_enrollments')->references('id')->on('enrollments')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}

	public function down()
	{
		Schema::table('installments', function (Blueprint $table) {
			$table->dropForeign('FK_installments_enrollments');
		});
	}
}