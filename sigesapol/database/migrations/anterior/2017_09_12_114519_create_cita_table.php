<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCitaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('citas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_farmacia')->nullable()->unsigned();
            $table->foreign('id_farmacia')->references('id')->on('farmacias');
            $table->integer('id_user')->nullable()->unsigned();
            $table->foreign('id_user')->references('id')->on('users');
            $table->integer('id_consultorio')->nullable()->unsigned();
            $table->foreign('id_consultorio')->references('id')->on('consultorios');
            $table->string('nro_cita', 10)->nullable();
            $table->string('dni_beneficiario', 8)->nullable();
            $table->string('nombre_beneficiario', 100)->nullable();
            $table->string('paterno_beneficiario', 100)->nullable();
            $table->string('materno_beneficiario', 100)->nullable();
            $table->string('tipo_beneficiario', 20)->nullable();
            $table->date('fecha_cita')->nullable();
			$table->string('tipo_cita', 50)->nullable();
			$table->string('nro_historia', 10)->nullable();
			$table->string('turno', 2)->nullable();
			$table->integer('id_medico')->nullable()->unsigned();
			$table->string('grado', 100)->nullable();
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
        //
    }
}
