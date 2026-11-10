<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecetaProcedimientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receta_procedimientos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_receta_vale')->nullable()->unsigned();
            $table->foreign('id_receta_vale')->references('id')->on('receta_vales');
            $table->integer('id_procedimiento')->nullable()->unsigned();
            $table->foreign('id_procedimiento')->references('id')->on('procedimientos');
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
        Schema::drop('receta_procedimientos');
    }
}
