<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCentroQuirurgicoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('centro_quirurgico', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_farmacia')->nullable()->unsigned();
            $table->foreign('id_farmacia')->references('id')->on('farmacias');
            $table->integer('id_user')->nullable()->unsigned();
            $table->foreign('id_user')->references('id')->on('users');
			
            $table->integer('id_consultorio')->nullable()->unsigned();
            $table->foreign('id_consultorio')->references('id')->on('consultorios');
			
			$table->integer('id_admision')->nullable()->unsigned();
			$table->string('origen_paciente', 100)->nullable();
			$table->string('sala', 100)->nullable();
			$table->string('turno', 2)->nullable();
			$table->string('tipo_anestesia', 50)->nullable();
			$table->integer('id_diagnostico')->nullable()->unsigned();
			
            //$table->string('dni_beneficiario', 8)->nullable();
            //$table->string('nombre_beneficiario', 100)->nullable();
            //$table->string('paterno_beneficiario', 100)->nullable();
            //$table->string('materno_beneficiario', 100)->nullable();
            //$table->string('tipo_beneficiario', 20)->nullable();
            $table->date('fecha_quirurgico')->nullable();
			//$table->string('nro_historia', 10)->nullable();
			
			$table->integer('id_medico_cirujano')->nullable()->unsigned();
			$table->integer('id_medico_anestesiologo')->nullable()->unsigned();
			$table->integer('id_medico_cirujano_ayudante')->nullable()->unsigned();
			$table->integer('id_enfermera_1')->nullable()->unsigned();
			$table->integer('id_enfermera_2')->nullable()->unsigned();
			
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
