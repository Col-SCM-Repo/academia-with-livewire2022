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
			$table->bigInteger('enrollment_id')->nullable()->unsigned()->index('FK_examsCode_enrollment');
            $table->bigInteger('type_code_id')->nullable()->unsigned()->index('FK_examsCode_typeCodes');
			$table->string('code_exam')->nullable();
			$table->string('enrollment_code')->unique();
			$table->string('surname', 100);
			$table->string('name', 100);
            $table->text('observation')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('student_exam_codes');
    }
}
