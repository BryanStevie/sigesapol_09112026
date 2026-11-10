<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecetaDevueltasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receta_devueltas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('numero_movimiento',20)->nullable()->unsigned();
            $table->integer('id_receta_vale')->nullable()->unsigned();
            $table->foreign('id_receta_vale')->references('id')->on('receta_vales');
            $table->date('fecha_devolucion')->nullable();
            $table->integer('id_farmacia')->nullable()->unsigned();
            $table->foreign('id_farmacia')->references('id')->on('farmacias');
            $table->integer('id_user')->nullable()->unsigned();
            $table->foreign('id_user')->references('id')->on('users');
            $table->nullableTimestamps();              
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('receta_devueltas');
    }
}
