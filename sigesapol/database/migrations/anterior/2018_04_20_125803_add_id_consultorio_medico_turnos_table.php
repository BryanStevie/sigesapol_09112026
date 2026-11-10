<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIdConsultorioMedicoTurnosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('medico_turnos', function (Blueprint $table) {
                $table->integer('id_consultorio')->nullable()->unsigned();
                $table->foreign('id_consultorio')->references('id')->on('consultorios');
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
