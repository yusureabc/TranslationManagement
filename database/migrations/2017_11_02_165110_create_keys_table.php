<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKeysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create( 'keys', function(Blueprint $table) {
            $table->increments( 'id' )->comment( '自增ID' );
            $table->unsignedInteger( 'project_id' )->default( 0 )->comment( '项目ID' );
            $table->string( 'key', 100 )->default('')->comment( 'key值' );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop( 'keys' );
    }
}
