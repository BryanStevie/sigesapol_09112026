<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteTablas2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		//Schema::drop('procedimientos');
		/*Schema::drop('receta_atendidas');
		Schema::drop('receta_stocks');
		Schema::drop('salida_diarias');
		Schema::drop('user_providers');*/
		/*Schema::drop('stock_farmacias');
		Schema::drop('stock_lotes');*/
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
