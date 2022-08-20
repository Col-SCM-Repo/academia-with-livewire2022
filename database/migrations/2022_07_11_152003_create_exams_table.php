<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamsTable extends Migration
{
    public function up()
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
            $table->integer('period_id')->unsigned()->index('FK_exams_period');
            $table->bigInteger('level_id')->unsigned()->nullable()->index('FK_exams_level');
            $table->bigInteger('group_id')->unsigned()->index('FK_exams_group');
            $table->string('group_code')->nullable();             // 2 digitos
            $table->string('nombre_examen');
            $table->dateTime('fecha_examen')->nullable();
            $table->bigInteger('user_id')->unsigned()->nullable()->index('FK_exams_user');
            $table->timestamps();
			$table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('exams');
    }
}
