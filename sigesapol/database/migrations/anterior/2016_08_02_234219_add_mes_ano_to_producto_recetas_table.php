<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMesAnoToProductoRecetasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('producto_recetas', function (Blueprint $table) {
            //
            $table->string('mes',2)->nullable();
            $table->string('ano',4)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('producto_recetas', function (Blueprint $table) {
            //
        });
    }
}
