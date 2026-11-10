<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFielsToGuiaEntradasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('guia_entradas', function (Blueprint $table) {
            //
            $table->integer('lugar_origen')->nullable();
            $table->integer('concepto')->nullable();
            $table->integer('tipo_doc')->nullable();
            $table->integer('siaf')->nullable();            
            $table->integer('proveedor_id')->nullable();
            $table->string('referencia', 50)->nullable();
            $table->string('fecha_doc_recepcion',10)->nullable();  
            $table->integer('tipo_compra')->nullable();
            $table->integer('tipo_proceso')->nullable();
            $table->string('meta',10)->nullable();
            $table->string('cadena_funcional', 50)->nullable();            
            $table->string('ff_rb',10)->nullable();
            $table->string('c_g', 15)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('guia_entradas', function (Blueprint $table) {
            //
        });
    }
}
