<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTipoDocumentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipo_documentos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('descripcion_tipo_documento',100);  
            $table->string('abreviatura_tipo_documento',10);  
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
        Schema::drop('tipo_documentos');
    }
}
