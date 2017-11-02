<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create( 'projects', function(Blueprint $table) {
            $table->increments( 'id' )->comment( '自增ID' );
            $table->string( 'name', 50 )->default('')->comment( '项目名称' );
            $table->text( 'description' )->comment( '项目描述' );
            $table->unsignedInteger( 'user_id' )->default( 0 )->comment( '用户ID' );
            $table->string( 'username', 100 )->default( '' )->comment( '用户名' );
            $table->string( 'language' )->default( '' )->comment( '需翻译语言' );
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
        Schema::drop( 'projects' );
    }
}
