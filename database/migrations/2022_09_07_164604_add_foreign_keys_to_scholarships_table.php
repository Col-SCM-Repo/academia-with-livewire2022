<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToScholarshipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('scholarships', function (Blueprint $table) {
            $table->foreign('enrollment_id')->references('id')->on('enrollments')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('type_scholarship_id')->references('id')->on('type_scholarships')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('user_id')->references('id')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scholarships', function (Blueprint $table) {
			$table->dropForeign('FK_scholarships_enrollment');
			$table->dropForeign('FK_scholarships_typeScholarship');
			$table->dropForeign('FK_scholarships_user');
        });
    }
}
