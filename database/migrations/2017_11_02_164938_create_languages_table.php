<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create( 'languages', function(Blueprint $table) {
            $table->increments( 'id' )->comment( '自增ID' );
            $table->unsignedInteger( 'project_id' )->comment( '项目ID' );
            $table->string( 'name', 50 )->default('')->comment( '语言名' );
            $table->tinyInteger( 'status' )->length( 1 )->comment( '状态  0 lock, 1 open' );
            $table->timestamp( 'download_at' )->default( DB::raw('CURRENT_TIMESTAMP') )->comment( '最后下载时间' );
            $table->timestamp( 'submit_at' )->default( DB::raw('CURRENT_TIMESTAMP') )->comment( 'translator最后提交时间' );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop( 'languages' );
    }
}
