<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDnisTable extends Migration
{
    public function up()
    {
        Schema::create('dnis', function (Blueprint $table) {
            $table->increments('id');            
            $table->string('dni',12)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('dnis');
    }
}
