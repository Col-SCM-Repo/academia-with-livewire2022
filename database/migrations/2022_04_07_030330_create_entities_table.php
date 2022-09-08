<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntitiesTable extends Migration
{
	public function up()
	{
		Schema::create('entities', function (Blueprint $table) {
			$table->bigInteger('id', true)->unsigned();
			$table->string('father_lastname', 100);
			$table->string('mother_lastname', 100);
			$table->string('name', 100);
			$table->text('address', 65535)->nullable();
			$table->bigInteger('district_id')->unsigned()->nullable()->index('FK_entities_districts');
			$table->string('telephone', 20)->nullable();
			$table->string('mobile_phone', 20)->nullable();
			$table->string('email', 50)->nullable()->unique();
			$table->date('birth_date')->nullable();
			$table->enum('gender', array('male', 'female'))->nullable();
			$table->bigInteger('country_id')->unsigned()->nullable()->index('FK_entities_countries');
			$table->enum('document_type', array('dni', 'foreigner_card', 'passport', 'other'))->default('dni');
			$table->string('document_number', 20)->unique();
			$table->enum('marital_status', array('single', 'married', 'divorcied', 'widower'))->nullable();
			$table->enum('instruction_degree', array('none', 'elementary_school', 'high_school', 'universitary_education'))->nullable();
			$table->string('photo_path', 100)->default('avatar_default.png')->nullable();;
			$table->softDeletes();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('entities');
	}
}
