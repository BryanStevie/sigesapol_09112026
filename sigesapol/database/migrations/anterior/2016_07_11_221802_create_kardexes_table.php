<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKardexesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kardexes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('codigo_producto',8)->nullable();
            $table->string('nombre_producto',255)->nullable();
            $table->string('tipo_movimiento',20)->nullable();
            $table->string('numero_movimiento',20)->nullable();
            $table->string('origen',255)->nullable();
            $table->string('destino',255)->nullable();
            $table->integer('estado')->nullable();
            $table->integer('cantidad')->nullable();
            $table->string('nombre_farmacia',255)->nullable();
            $table->date('fecha_movimiento')->nullable();
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
        Schema::drop('kardexes');
    }
}
