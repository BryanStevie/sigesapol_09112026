<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductoAnaquelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('producto_anaqueles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_producto')->nullable();
			$table->integer('id_anaquel')->nullable()->unsigned();
            $table->foreign('id_anaquel')->references('id')->on('anaqueles');
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
