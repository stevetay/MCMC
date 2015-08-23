<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdvertisementTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('advertisements', function(Blueprint $table)
		{
			$table->increments('adv_id');
			$table->string('adv_long');
			$table->string('adv_lat');
			$table->string('adv_title');
			$table->string('adv_thumbnail');
			$table->string('adv_desc');
			$table->string('adv_url');
			$table->string('adv_ispersonal');
			$table->string('adv_status');
			$table->string('adv_createdate');
			$table->string('adv_expdate');
			$table->string('user_id');
			$table->string('adv_mime');
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
		Schema::table('advertisements', function(Blueprint $table)
		{
			Schema::drop('advertisement');
		});
	}

}
