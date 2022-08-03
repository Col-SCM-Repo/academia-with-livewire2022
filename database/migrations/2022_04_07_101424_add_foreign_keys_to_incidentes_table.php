<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToIncidentesTable extends Migration {
    public function up()
    {
        Schema::table('incidentes', function (Blueprint $table) {
			$table->foreign('enrollment_id', 'FK_incidentes_enrollment')->references('id')->on('enrollments')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('auxiliar_id', 'FK_auxiliar_usuarios')->references('id')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('secretaria_id', 'FK_secretaria_usuarios')->references('id')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });
    }

    public function down()
    {
        Schema::table('incidentes', function (Blueprint $table) {
            $table->dropForeign('FK_incidentes_enrollment');
            $table->dropForeign('FK_auxiliar_usuarios');
            $table->dropForeign('FK_secretaria_usuarios');
        });
    }
}
