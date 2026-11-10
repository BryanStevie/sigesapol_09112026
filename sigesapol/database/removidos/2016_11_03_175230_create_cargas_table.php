<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCargasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cargas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('codigo',10)->nullable();
            $table->string('nombre',255)->nullable();
            $table->string('registro_sanitario',12)->nullable();
            $table->string('lote',12)->nullable();
            $table->integer('cantidad')->nullable();
            $table->date('fecha_vencimiento')->nullable();            
            $table->integer('id_farmacia')->nullable()->unsigned();
            $table->foreign('id_farmacia')->references('id')->on('farmacias');
            $table->date('fecha_ingreso')->nullable();
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
        Schema::drop('cargas');
    }
}
