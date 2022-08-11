<?php

use App\Enums\EstadosEnum;
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
			$table->enum('type', array('normal', 'beca', 'semi-beca'));
			$table->bigInteger('student_id')->unsigned()->index('FK_enrollments_students');
			$table->bigInteger('classroom_id')->unsigned()->index('FK_enrollments_classrooms');
			$table->bigInteger('relative_id')->unsigned()->index('FK_enrollments_relatives');
			$table->enum('relative_relationship', array('father', 'mother', 'brother', 'sister', 'uncle', 'grandparent', 'cousin', 'other'));
			$table->bigInteger('user_id')->unsigned()->index('FK_enrollments_users');
			$table->bigInteger('career_id')->unsigned()->index('FK_enrollments_carreer');
			$table->enum('payment_type', array('cash', 'credit'));
			$table->integer('fees_quantity')->unsigned()->default(0);
			$table->decimal('period_cost', 10);
			$table->integer('status')->default(EstadosEnum::ACTIVO);
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
