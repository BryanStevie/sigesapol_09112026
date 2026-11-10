<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFielsToHospitalizacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hospitalizaciones', function (Blueprint $table) {
            //
            $table->integer('id_diagnostico_hospitalizacion')->nullable()->unsigned();
            $table->foreign('id_diagnostico_hospitalizacion')->references('id')->on('diagnosticos');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hospitalizaciones', function (Blueprint $table) {
            //
        });
    }
}
