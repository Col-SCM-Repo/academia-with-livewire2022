<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEvidenciasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evidencias', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('incidente_id', false, true);
            $table->string('evidencia_descripcion', 250)->nullable();
            $table->string('path', 300)->nullable();
            $table->enum('estado', array('-1', '0', '1'))->default(0);
            $table->integer('user_id', false, true)->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('incidente_id', 'FK_evidencias_incidentes')->references('id')->on('incidentes')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('user_id', 'FK_evidencias_usuarios')->references('id')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
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
        Schema::dropIfExists('evidencias');
    }
}
