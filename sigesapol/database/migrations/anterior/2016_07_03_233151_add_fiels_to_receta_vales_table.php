<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFielsToRecetaValesTable extends Migration
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
            $table->integer('id_diagnostico')->nullable()->unsigned();
            $table->foreign('id_diagnostico')->references('id')->on('diagnosticos');
            $table->string('nro_historia',10)->nullable();
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
