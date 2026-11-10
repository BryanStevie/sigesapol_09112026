<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGeneraNeaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('genera_correlativonea', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_farmacia')->references('id')->on('farmacias');
            $table->integer('anio_nea');
            $table->integer('nro_nea');
            $table->integer('estado');
            $table->integer('id_user_created')->references('id')->on('users')->nullable();
            $table->integer('id_user_update')->references('id')->on('users')->nullable();
            $table->nullableTimestamps(); //Este crea el created_at y el update_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('genera_correlativonea');
    }
}
