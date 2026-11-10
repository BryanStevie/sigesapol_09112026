<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMotivoTransferenciaTable_ extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('motivo_transferencia', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('orden');
            $table->string('nombre', 150);
            $table->string('abreviatura', 50)->nullable();
            $table->string('tipo_motivo', 1);
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
         Schema::drop('motivo_transferencia');
    }
}
