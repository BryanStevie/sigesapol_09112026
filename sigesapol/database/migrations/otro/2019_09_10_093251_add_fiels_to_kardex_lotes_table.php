<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFielsToKardexLotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kardex_lotes', function (Blueprint $table) {
            $table->integer('id_ingreso_salida')->nullable()->unsigned();
            $table->integer('estado')->default(1)->unsigned();            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kardex_lotes', function (Blueprint $table) {
            //
        });
    }
}
