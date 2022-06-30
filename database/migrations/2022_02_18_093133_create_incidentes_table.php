<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIncidentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incidentes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('enrollment_id', false, true);
			$table->integer('auxiliar_id', false, true)->nullable();
			$table->integer('secretaria_id', false, true)->nullable();
			$table->string('tipo_incidente',50)->nullable();
			$table->string('descripcion', 300);
			$table->string('justificacion', 300)->nullable();
			$table->string('parentesco', 30)->nullable();
			$table->enum('estado', array('-1','0','1'))->default(0);

            
            $table->dateTime('fecha_reporte')->nullable();
            $table->softDeletes();
            $table->timestamps();
			
			$table->foreign('enrollment_id', 'FK_incidentes_enrollment')->references('id')->on('enrollments')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('auxiliar_id', 'FK_auxiliar_usuarios')->references('id')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('secretaria_id', 'FK_secretaria_usuarios')->references('id')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');

            // $table->foreign('province_id', 'FK_districts_provinces')->references('id')->on('provinces')->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('incidentes');
    }
}
