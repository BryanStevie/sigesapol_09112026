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
        Schema::create('hospitalizaciones', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_farmacia')->nullable()->unsigned();
            $table->foreign('id_farmacia')->references('id')->on('farmacias');
            $table->string('nro_hospitalizacion', 10)->nullable();
			$table->string('dni_beneficiario', 8)->nullable();
            $table->string('nombre_beneficiario', 100)->nullable();
            $table->string('paterno_beneficiario', 100)->nullable();
            $table->string('materno_beneficiario', 100)->nullable();
            $table->string('dni_titular', 8)->nullable();
            $table->string('nombre_titular', 100)->nullable();
            $table->string('paterno_titular', 100)->nullable();
            $table->string('materno_titular', 100)->nullable();
            $table->date('fecha_nacimiento_beneficiario')->nullable();
            $table->integer('edad')->nullable()->unsigned();
            $table->string('sexo', 2)->nullable();
            $table->string('tipo_beneficiario', 20)->nullable();
            $table->string('nro_historia', 10)->nullable();
            $table->string('turno', 2)->nullable();
            $table->string('turno_salida', 2)->nullable();
            $table->string('tarifa', 2)->nullable();
            $table->string('origen', 2)->nullable();
            $table->string('grado', 100)->nullable();
            $table->integer('id_diagnostico_inicial')->nullable()->unsigned();
            $table->foreign('id_diagnostico_inicial')->references('id')->on('diagnosticos');
            $table->integer('id_diagnostico_hospitalizacion')->nullable()->unsigned();
            $table->foreign('id_diagnostico_hospitalizacion')->references('id')->on('diagnosticos');
            $table->integer('id_diagnostico_final')->nullable()->unsigned();
            $table->foreign('id_diagnostico_final')->references('id')->on('diagnosticos');
            $table->string('condicion', 2)->nullable();
			$table->datetime('fecha_ingreso')->nullable();
			$table->datetime('fecha_salida')->nullable();
            $table->integer('id_medico')->nullable()->unsigned();
            $table->integer('id_medico_alta')->nullable()->unsigned();
            $table->integer('id_pabellon')->nullable()->unsigned();
			$table->integer('id_piso')->nullable()->unsigned();
			$table->integer('id_consultorio_hosp')->nullable()->unsigned();
            $table->foreign('id_consultorio_hosp')->references('id')->on('consultorios');
            $table->string('nombre_consultorio', 100)->nullable();
			$table->integer('id_cuarto')->nullable()->unsigned();
			$table->integer('id_cama')->nullable()->unsigned();
			$table->integer('estado')->nullable();
            $table->integer('id_consultorio')->nullable()->unsigned();
            $table->foreign('id_consultorio')->references('id')->on('consultorios');
            $table->integer('id_user')->nullable()->unsigned();
            $table->foreign('id_user')->references('id')->on('users');
            $table->string('nombre_registrador', 100)->nullable();
            $table->integer('id_user_alta')->nullable()->unsigned();
            $table->foreign('id_user_alta')->references('id')->on('users');
            $table->string('nombre_registrador_alta', 100)->nullable();
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
