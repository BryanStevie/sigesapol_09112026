<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockFarmaciasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_farmacias', function (Blueprint $table) {
            $table->increments('id');
            $table->string('codigo_producto',8)->nullable();
            $table->string('nombre_producto',255)->nullable();
            $table->integer('cantidad')->nullable();
            $table->string('nombre_farmacia',255)->nullable();
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
        Schema::drop('stock_farmacias');
    }
}
