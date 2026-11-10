<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecetaStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receta_stocks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_receta')->nullable()->unsigned();
            $table->foreign('id_receta')->references('id')->on('receta_atendidas');
            $table->integer('id_stock')->nullable()->unsigned();
            $table->foreign('id_stock')->references('id')->on('stocks');
            $table->integer('cantidad_solicitada')->nullable();
            $table->integer('cantidad_atendida')->nullable();
            $table->integer('estado')->nullable();
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
        Schema::drop('receta_stocks');
    }
}
