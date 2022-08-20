<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentExamCodesTable extends Migration
{
    public function up()
    {
        Schema::create('student_exam_codes', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
			$table->bigInteger('student_id')->unsigned()->index('FK_examsCode_student');
			$table->string('student_code')->nullable();
			$table->string('code_exam')->nullable();
			$table->text('observation')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('student_exam_codes');
    }
}
