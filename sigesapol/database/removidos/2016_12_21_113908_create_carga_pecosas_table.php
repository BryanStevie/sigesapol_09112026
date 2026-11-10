<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCargaPecosasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carga_pecosas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_producto')->nullable()->unsigned();
            $table->foreign('id_producto')->references('id')->on('productos');
            $table->string('registro_sanitario',25)->nullable();
            $table->string('lote',25)->nullable();
            $table->date('fecha_vencimiento')->nullable();
            $table->date('fecha_carga')->nullable();
            $table->integer("cantidad")->nullable();
            $table->integer('id_guia_entrada')->nullable()->unsigned();
            $table->foreign('id_guia_entrada')->references('id')->on('guia_entradas');
            $table->integer('id_farmacia')->nullable()->unsigned();
            $table->foreign('id_farmacia')->references('id')->on('farmacias');
            $table->integer("estado")->nullable(); //1: carga pendiente , 2: carga confirmada
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
        Schema::drop('carga_pecosas');
    }
}
