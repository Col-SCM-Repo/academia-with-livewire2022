<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupsTable extends Migration
{
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
			$table->string('description', 12);
			$table->timestamps();
        });
    }

    public function down()
    {
        Schema::table('groups', function (Blueprint $table) {
            Schema::drop('groups');
        });
    }
}