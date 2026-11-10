<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIdMedicoToRecetaValesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('receta_vales', function (Blueprint $table) {
            //
            $table->integer('id_medico')->nullable()->unsigned();
            $table->foreign('id_medico')->references('id')->on('medicos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('receta_vales', function (Blueprint $table) {
            //
        });
    }
}
