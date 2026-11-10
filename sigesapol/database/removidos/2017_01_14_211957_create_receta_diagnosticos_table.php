<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecetaDiagnosticosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receta_diagnosticos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_receta_vale')->nullable()->unsigned();
            $table->foreign('id_receta_vale')->references('id')->on('receta_vales');
            $table->integer('id_diagnostico')->nullable()->unsigned();
            $table->foreign('id_diagnostico')->references('id')->on('diagnosticos');
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
        Schema::drop('receta_diagnosticos');
    }
}
