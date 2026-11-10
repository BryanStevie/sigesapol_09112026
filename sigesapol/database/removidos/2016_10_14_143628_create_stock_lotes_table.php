<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockLotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_lotes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_stock_farmacia')->nullable()->unsigned();            
            $table->foreign('id_stock_farmacia')->references('id')->on('stock_farmacias');
            $table->string('registro_sanitario',12)->nullable();
            $table->string('lote',12)->nullable();
            $table->integer('cantidad')->nullable();
            $table->date('fecha_vencimiento')->nullable();
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
        Schema::drop('stock_lotes');
    }
}
