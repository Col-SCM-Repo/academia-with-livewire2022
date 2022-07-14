<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnrollmentsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('enrollments', function (Blueprint $table) {
			$table->bigInteger('id', true)->unsigned();
			$table->string('code')->nullable();
			$table->enum('type', array('normal', 'beca', 'semi-beca'));
			$table->bigInteger('student_id')->unsigned()->index('enrollments_student_id_foreign');
			$table->bigInteger('classroom_id')->unsigned()->index('enrollments_section_id_foreign');
			$table->bigInteger('relative_id')->unsigned()->index('enrollments_relative_id_foreign');
			$table->enum('relative_relationship', array('father', 'mother', 'brother', 'sister', 'uncle', 'grandparent', 'cousin', 'other'));
			$table->bigInteger('user_id')->unsigned()->index('enrollments_user_id_foreign');
			//$table->text('career', 65535);
			$table->bigInteger('career_id')->unsigned()->index('enrollments_career_id_foreign');
			$table->enum('payment_type', array('cash', 'credit'));
			$table->integer('fees_quantity')->unsigned()->default(0);
			$table->decimal('period_cost', 10);
			$table->integer('cancelled')->default(0);
			$table->text('observations', 65535)->nullable();
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
		Schema::drop('enrollments');
	}
}
