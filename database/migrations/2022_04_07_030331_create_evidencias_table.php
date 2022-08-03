<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEvidenciasTable extends Migration
{
    public function up()
    {
        Schema::create('evidencias', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->bigInteger('incidente_id')->unsigned()->index('FK_evidencias_incidentes');
            $table->string('evidencia_descripcion', 250)->nullable();
            $table->string('path', 300)->nullable();
            $table->enum('estado', array('-1', '0', '1'))->default(0);
            $table->bigInteger('user_id')->unsigned()->nullable()->index('FK_evidencias_usuarios');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('evidencias');
    }
}
