<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFielsToMedicoTurnosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('medico_turnos', function (Blueprint $table) {
            $table->string('id_tipo_atencion', 15)->default('AMBULATORIO');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('medico_turnos', function (Blueprint $table) {
            //
        });
    }
}
