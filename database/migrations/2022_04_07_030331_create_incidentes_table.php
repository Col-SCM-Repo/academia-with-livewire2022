<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIncidentesTable extends Migration
{
    public function up()
    {
        Schema::create('incidentes', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->bigInteger('enrollment_id')->unsigned()->index('FK_incidentes_enrollment');
			$table->bigInteger('auxiliar_id')->unsigned()->nullable()->index('FK_auxiliar_usuarios');
			$table->bigInteger('secretaria_id')->unsigned()->nullable()->index('FK_secretaria_usuarios');
			$table->string('tipo_incidente',50)->nullable();
			$table->string('descripcion', 300);
			$table->string('justificacion', 300)->nullable();
			$table->string('parentesco', 30)->nullable();
			$table->enum('estado', array('-1','0','1'))->default(0);

            
            $table->dateTime('fecha_reporte')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('incidentes');
    }
}
