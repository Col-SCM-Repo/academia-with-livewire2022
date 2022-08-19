<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Courses extends Migration
{
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
            $table->string('name');
            $table->enum('status', array(1, 0))->default(1);
            $table->bigInteger('academic_area_id')->unsigned()->index('FK_courses_academicArea');
            $table->bigInteger('user_id')->unsigned()->nullable()->index('FK_courses_user');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('courses');
    }
}
