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
            $table->integer('id_anestesiologo')->nullable()->unsigned();
            $table->foreign('id_anestesiologo')->references('id')->on('medicos');
            
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
