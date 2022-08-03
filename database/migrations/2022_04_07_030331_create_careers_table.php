<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCareersTable extends Migration
{
    public function up()
    {
        Schema::create('careers', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
			$table->bigInteger('group_id')->unsigned()->index('FK_career_group');
			$table->text('career', 65535);
			$table->string('nmonico', 10);
			$table->timestamps();
        });
    }

    public function down()
    {
        Schema::table('careers', function (Blueprint $table) {
            Schema::drop('careers');
        });
    }
}
