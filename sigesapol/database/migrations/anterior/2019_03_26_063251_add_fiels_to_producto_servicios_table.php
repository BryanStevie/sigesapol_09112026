<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFielsToProductoServiciosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('producto_servicios', function (Blueprint $table) {
            //
            $table->integer('id_farmacia')->default(3)->unsigned();
            $table->foreign('id_farmacia')->references('id')->on('farmacias');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('producto_servicios', function (Blueprint $table) {
            //
        });
    }
}
