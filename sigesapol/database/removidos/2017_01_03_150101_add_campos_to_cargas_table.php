<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCamposToCargasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cargas', function (Blueprint $table) {
            //
            $table->integer('id_producto')->nullable()->unsigned();
            $table->foreign('id_producto')->references('id')->on('productos');
            $table->integer('id_carga_inicial')->nullable()->unsigned();
            $table->foreign('id_carga_inicial')->references('id')->on('carga_inicial_stocks');
            $table->integer('estado')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cargas', function (Blueprint $table) {
            //
        });
    }
}
