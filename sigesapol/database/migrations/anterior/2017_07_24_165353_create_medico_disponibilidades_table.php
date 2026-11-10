<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMedicoDisponibilidadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medico_turnos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_medico')->nullable()->unsigned();
			$table->integer('dia')->nullable();
            $table->string('turno',20)->nullable();
            $table->string('numero_atencion',200)->nullable();
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
