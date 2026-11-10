<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIdFarmaciaImportacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('importaciones', function (Blueprint $table) {
            $table->integer('id_farmacia')->nullable()->unsigned();
            $table->foreign('id_farmacia')->references('id')->on('farmacias');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('importaciones', function (Blueprint $table) {
            //
        });
    }
}
