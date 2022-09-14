<?php

use App\Enums\EstadosEnum;
use App\Enums\EstadosMatriculaEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnrollmentsTable extends Migration
{
	public function up()
	{
		Schema::create('enrollments', function (Blueprint $table) {
			$table->bigInteger('id', true)->unsigned();
			$table->string('code')->nullable();
			$table->enum('type', array('normal', 'beca', 'semi-beca'))->nullable();;
			$table->bigInteger('student_id')->unsigned()->index('FK_enrollments_students');
			$table->bigInteger('classroom_id')->unsigned()->index('FK_enrollments_classrooms');
			$table->bigInteger('user_id')->unsigned()->index('FK_enrollments_users');
			$table->bigInteger('career_id')->unsigned()->index('FK_enrollments_carreer');
			$table->bigInteger('scholarship_id')->unsigned()->nullable()->index('FK_enrollments_scholarship');
			$table->enum('payment_type', array('cash', 'credit'))->nullable();
			$table->integer('fees_quantity')->unsigned()->default(0);
			$table->decimal('period_cost', 10)->nullable();
			$table->decimal('period_cost_final', 10)->nullable();
			$table->decimal('amount_paid', 10)->default(0);
			$table->integer('status')->default(EstadosMatriculaEnum::PENDIENTE_ACTIVACION);
			$table->text('observations', 65535)->nullable();
			$table->softDeletes();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('enrollments');
	}
}
