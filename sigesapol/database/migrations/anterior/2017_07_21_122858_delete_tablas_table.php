<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteTablasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Schema::drop('compare');
		//Schema::drop('contador_recetas');
		//Schema::drop('junta_medicas');
		/*Schema::drop('procedimientos');
		Schema::drop('receta_atendidas');
		Schema::drop('receta_stocks');
		Schema::drop('salida_diarias');
		Schema::drop('stock_farmacias');
		Schema::drop('stock_lotes');
		Schema::drop('user_providers');*/
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
