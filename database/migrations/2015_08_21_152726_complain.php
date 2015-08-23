<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Complain extends Migration {

	/**
	 * Run the migrations.
	 * 
	 * **/
	 * @return void
	 */
	public function up()
	{
		Schema::table('complain', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('complained_by');
			$table->string('long');
			$table->string('lat');
			$table->string('address');
			$table->string('title');
			$table->string('description');
			$table->string('category');
			$table->string('thumbnail');
			$table->string('created_on');
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
		Schema::table('complain', function(Blueprint $table)
		{
			Schema::drop('complain');
		});
	}

}
