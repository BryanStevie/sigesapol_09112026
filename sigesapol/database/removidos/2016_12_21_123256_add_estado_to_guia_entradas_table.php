<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEstadoToGuiaEntradasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('guia_entradas', function (Blueprint $table) {
            //
            $table->integer("estado")->nullable(); //1: pecosa pendiente , 2: pecosa ingresada
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('guia_entradas', function (Blueprint $table) {
            //
        });
    }
}
