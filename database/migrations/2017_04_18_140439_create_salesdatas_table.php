<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesdatasTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('salesdatas', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('num')->unsigned()->default(0)->comment('销售数量');
            $table->integer('platform_id')->unsigned()->index()->comment('对应平台ID');
            $table->integer('product_id')->unsigned()->index()->comment('对应产品ID');
            $table->float('amount', 12, 2)->default('0.00')->comment('销售金额');
            $table->date('data_time')->index()->comment('数据日期');
            $table->timestamps();

            $table->foreign('platform_id')
                ->references('id')->on('platforms')
                ->onDelete('cascade');
            $table->foreign('product_id')
                ->references('id')->on('products')
                ->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('salesdatas');
	}

}
