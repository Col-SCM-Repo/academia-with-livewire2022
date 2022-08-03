<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToUsersTable extends Migration {
	public function up()
	{
		Schema::table('users', function(Blueprint $table)
		{
			$table->foreign('entity_id', 'FK_users_entities')->references('id')->on('entities')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}

	public function down()
	{
		Schema::table('users', function(Blueprint $table)
		{
			$table->dropForeign('FK_users_entities');
		});
	}

}
