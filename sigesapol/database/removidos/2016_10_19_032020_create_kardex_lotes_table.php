<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKardexLotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kardex_lotes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_stock_lote')->nullable()->unsigned();            
            $table->foreign('id_stock_lote')->references('id')->on('stock_lotes');
            $table->integer('id_kardex')->nullable()->unsigned();            
            $table->foreign('id_kardex')->references('id')->on('kardexes');
            $table->integer('cantidad')->nullable();
            $table->date('fecha_registro')->nullable();
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
        Schema::drop('kardex_lotes');
    }
}
