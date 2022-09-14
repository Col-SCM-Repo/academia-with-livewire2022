<?php

use App\Enums\TipoDescuentosEnum;
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

			$table->enum('type_scholarship', [TipoDescuentosEnum::PORCENTAJE, TipoDescuentosEnum::MONTO_FIJO, TipoDescuentosEnum::OTRO ]);

            $table->text('description')->nullable();
            $table->double('parameter_discount')->unsigned()->default(0);
            $table->timestamps();
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
