<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToCareersTable extends Migration {
    public function up()
    {
        Schema::table('careers', function (Blueprint $table) {
            $table->foreign('group_id', 'FK_career_group')->references('id')->on('groups')->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });
    }

    public function down()
    {
        Schema::table('careers', function (Blueprint $table) {
            $table->dropForeign('FK_career_group');
        });
    }
}
