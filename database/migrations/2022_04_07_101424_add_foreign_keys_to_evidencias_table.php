<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToEvidenciasTable extends Migration {
    public function up()
    {
        Schema::table('evidencias', function (Blueprint $table) {
            //
            $table->foreign('incidente_id', 'FK_evidencias_incidentes')->references('id')->on('incidentes')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('user_id', 'FK_evidencias_usuarios')->references('id')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            // $table->foreign('group_id', 'FK_career_group')->references('id')->on('groups')->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });
    }

    public function down()
    {
        Schema::table('evidencias', function (Blueprint $table) {
            $table->dropForeign('FK_evidencias_incidentes');
            $table->dropForeign('FK_evidencias_usuarios');
        });
    }
}
