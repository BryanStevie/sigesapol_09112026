<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHospitalizacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hospitalizacione', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_farmacia')->nullable()->unsigned();
            $table->foreign('id_farmacia')->references('id')->on('farmacias');
            $table->integer('id_user')->nullable()->unsigned();
            $table->foreign('id_user')->references('id')->on('users');
			$table->string('nro_hospitalizacion', 10)->nullable();
			$table->integer('id_admision')->nullable()->unsigned();
			$table->foreign('id_admision')->references('id')->on('admisiones');
			$table->datetime('fecha_ingreso')->nullable();
			$table->datetime('fecha_salida')->nullable();
            $table->integer('id_medico')->nullable()->unsigned();
            $table->integer('id_pabellon')->nullable()->unsigned();
			$table->integer('id_piso')->nullable()->unsigned();
			$table->integer('id_consultorio')->nullable()->unsigned();
			$table->integer('id_cuarto')->nullable()->unsigned();
			$table->integer('id_cama')->nullable()->unsigned();
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
