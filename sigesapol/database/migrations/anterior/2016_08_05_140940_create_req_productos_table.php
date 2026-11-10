<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReqProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('req_productos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_requerimiento')->nullable()->unsigned();
            $table->foreign('id_requerimiento')->references('id')->on('requerimientos');
            $table->string('codigo_producto',12)->nullable();
            $table->integer('cantidad')->nullable();
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
        Schema::drop('req_productos');
    }
}
