<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScholarshipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scholarships', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();

			$table->bigInteger('enrollment_id')->unsigned()->index('FK_scholarships_enrollment');
			$table->bigInteger('type_scholarship_id')->unsigned()->index('FK_scholarships_typeScholarship');
            $table->bigInteger('user_id')->unsigned()->nullable()->index('FK_scholarships_user');

            $table->text('description')->nullable();
            $table->double('discount')->unsigned()->default(0);
            $table->double('parameter_discount')->unsigned()->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scholarships');
    }
}
