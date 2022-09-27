<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamsTable extends Migration
{
    public function up()
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
            $table->bigInteger('period_id')->index('FK_exams_period');
            $table->bigInteger('level_id')->nullable()->index('FK_exams_level');
            $table->bigInteger('group_id')->unsigned()->index('FK_exams_group');
            $table->string('group_code')->nullable();             // 2 primeros digitos
            $table->string('name');
            $table->integer('number_questions')->unsigned();
            $table->double('score_wrong')->unsigned()->default(1);
			$table->enum('evaluation_type', array( 'simulacrum','monthly','weekly','daily', 'quick', 'other'));
            $table->dateTime('exam_date')->nullable();
            $table->bigInteger('user_id')->unsigned()->nullable()->index('FK_exams_user');
            $table->string('path')->nullable();
            $table->timestamps();
			$table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('exams');
    }
}
