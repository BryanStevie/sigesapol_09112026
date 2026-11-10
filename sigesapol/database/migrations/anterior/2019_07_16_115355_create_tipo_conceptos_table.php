<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTipoConceptosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipo_conceptos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('descripcion_tipo_concepto',100);  
            $table->string('abreviatura_tipo_concepto',10);  
            $table->integer('estado')->nullable();
            $table->integer('id_concepto');
            $table->foreign('id_concepto')->references('id')->on('conceptos');
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
        Schema::drop('tipo_conceptos');
    }
}
