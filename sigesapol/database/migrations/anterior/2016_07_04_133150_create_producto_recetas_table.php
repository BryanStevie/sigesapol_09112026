<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductoRecetasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('producto_recetas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_receta_vale')->nullable()->unsigned();
            $table->foreign('id_receta_vale')->references('id')->on('receta_vales');
            $table->integer('id_producto')->nullable()->unsigned();
            $table->foreign('id_producto')->references('id')->on('productos');
            $table->integer('cantidad_prescrita')->nullable();
            $table->integer('cantidad_dispensada')->nullable();
            $table->smallInteger('estado')->nullable();
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
        Schema::drop('producto_recetas');
    }
}
