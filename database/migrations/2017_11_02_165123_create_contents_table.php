<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create( 'contents', function(Blueprint $table) {
            $table->increments( 'id' )->comment( '自增ID' );
            $table->unsignedInteger( 'project_id' )->default( 0 )->comment( '项目ID' );
            $table->unsignedInteger( 'language_id' )->default( 0 )->comment( '语言ID' );
            $table->unsignedInteger( 'key_id' )->default( 0 )->comment( 'key ID' );
            $table->string( 'content' )->default('')->comment( '翻译内容' );
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
        Schema::drop( 'contents' );
    }
}
