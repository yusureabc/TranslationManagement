<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTranslatorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create( 'translators', function(Blueprint $table) {
            $table->increments( 'id' )->comment( '自增ID' );
            $table->unsignedInteger( 'project_id' )->default( 0 )->comment( '项目ID' );
            $table->string( 'project_name', 50 )->default('')->comment( '项目名称' );
            $table->unsignedInteger( 'language_id' )->default( 0 )->comment( '语言ID' );
            $table->string( 'language_name', 50 )->default('')->comment( '语言名称' );
            $table->unsignedInteger( 'user_id' )->default( 0 )->comment( '用户ID' );
            $table->string( 'username', 100 )->default( '' )->comment( '用户名' );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop( 'translators' );
    }
}
