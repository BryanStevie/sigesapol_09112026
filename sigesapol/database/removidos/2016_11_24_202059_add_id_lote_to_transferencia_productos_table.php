<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIdLoteToTransferenciaProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transferencia_productos', function (Blueprint $table) {
            //
            $table->integer('id_stock_lote')->nullable()->unsigned();
            $table->foreign('id_stock_lote')->references('id')->on('stock_lotes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transferencia_productos', function (Blueprint $table) {
            //
        });
    }
}
