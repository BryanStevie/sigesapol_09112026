<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransferenciasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transferencias', function (Blueprint $table) {
            $table->increments('id');
            $table->string("nro_acta",30)->nullable();
            $table->integer("tipo")->nullable();
            $table->date('fecha')->nullable();
            $table->string('hora_entrada',25)->nullable();
            $table->string('hora_salida',25)->nullable();
            $table->text("lugar")->nullable();
            //$table->string("dni_jefe_farmacia_local",8)->nullable();
            $table->string("nombre_jefe_farmacia_local",255)->nullable();
            //->string("dni_jefe_farmacia_local",8)->nullable();
            $table->string("nombre_jefe_servicio",255)->nullable();
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
        Schema::drop('transferencias');
    }
}
