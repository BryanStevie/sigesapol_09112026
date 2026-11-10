<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMedicoConsultorioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medico_consultorios', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_establecimiento')->references('id')->on('establecimientos');
            $table->integer('id_medico')->references('id')->on('medicos');
            $table->integer('id_consultorio')->references('id')->on('consultorios');
            $table->integer('estado');
            $table->integer('id_user_created')->references('id')->on('users')->nullable();
            $table->integer('id_user_update')->references('id')->on('users')->nullable();
            $table->nullableTimestamps(); //Este crea el created_at y el update_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
