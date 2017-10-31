<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlatformsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('platforms', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name')->default('')->comment('平台名称');
            $table->string('slug')->unique()->comment('平台标识');
            $table->string('url')->default('')->comment('平台网址');
            $table->string('logo')->default('')->comment('平台LOGO');
            $table->tinyInteger('sort')->default(0)->comment('排序');
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
		Schema::drop('platforms');
	}

}
