<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('codigo',8)->nullable();
            $table->string('nombre',300)->nullable();
            $table->integer('id_unidad')->nullable()->unsigned();
            $table->foreign('id_unidad')->references('id')->on('unidades');
            $table->integer('id_tipo')->nullable()->unsigned();
            $table->foreign('id_tipo')->references('id')->on('tipo_productos');
            $table->string('estado',1)->nullable();
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
        Schema::drop('productos');
    }
}
