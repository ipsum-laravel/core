<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateWebsiteTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('website', function(Blueprint $table)
        {
            $table->text('type')->nullable();
            $table->text('aide')->nullable();
            $table->text('validation')->nullable();
            $table->text('class')->nullable();
        });
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('website', function(Blueprint $table)
        {
            $table->drop('type');
            $table->drop('aide');
            $table->drop('validation');
        });
	}

}
