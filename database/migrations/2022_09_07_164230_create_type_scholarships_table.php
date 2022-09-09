<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTypeScholarshipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('type_scholarships', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();

            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', array( 'precentage', 'precentage_dinamic', 'static','static_dinamic', 'other'))->nullable(); // descuento 20 % del monto, S/ 50, otro
            $table->double('value')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('type_scholarships');
    }
}
