<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOcupacionTable extends Migration
{
    public function up()
    {
        Schema::create('occupations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 250)->nullable();
            $table->timestamps();
            // 
        });
    }

    public function down()
    {
        Schema::drop('occupations');
    }
}
