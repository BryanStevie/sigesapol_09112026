<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrama3Table extends Migration
{
    public function up()
    {
        Schema::create('trama3', function (Blueprint $table) {
            $table->increments('id');            
            $table->integer('id_excel')->nullable();
            $table->string('trama_original',1000)->nullable();
            $table->string('observacion',1000)->nullable();
            $table->string('observacion_original',1000)->nullable();
            $table->string('hospitalizacion',20)->nullable();
            $table->integer('carga_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('trama3');
    }
}
