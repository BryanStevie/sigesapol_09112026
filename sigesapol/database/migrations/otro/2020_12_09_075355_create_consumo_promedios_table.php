<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class CreateConsumoPromediosTable extends Migration
{
    public function up()
    {
        Schema::create('consumo_promedios', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_producto');
            $table->integer('id_farmacia');
            $table->integer('id_tipo');
            $table->integer('id_unidad');
            $table->string('cod_producto');     
            $table->double('cpm')->default(1);
            $table->double('cantidad_minima')->default(1);
            $table->integer('id_user_register')->nullable();       
            $table->integer('id_user_modific')->nullable();       
            $table->integer('estado')->default(1);
            $table->nullableTimestamps();
        });
    }

    public function down()
    {
        Schema::drop('consumo_promedios');
    }
    
}
