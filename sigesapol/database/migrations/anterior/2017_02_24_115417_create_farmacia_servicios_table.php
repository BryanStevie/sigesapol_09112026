<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFarmaciaServiciosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
		Schema::create('farmacia_servicios', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('id_farmacia')->nullable()->unsigned();
			$table->integer('id_servicio')->nullable()->unsigned();    
			$table->nullableTimestamps();
		});
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
