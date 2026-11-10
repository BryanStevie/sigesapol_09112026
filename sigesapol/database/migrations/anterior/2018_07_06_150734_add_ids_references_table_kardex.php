<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIdsReferencesTableKardex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kardexes', function (Blueprint $table) {
                $table->integer('id_producto')->references('id')->on('productos')->nullable();
                $table->integer('id_nro_movimiento')->nullable()->unsigned();
                $table->integer('id_origen')->references('id')->on('farmacias')->nullable();
                $table->integer('id_destino')->references('id')->on('farmacias')->nullable();
                $table->integer('id_farmacia')->references('id')->on('farmacias')->nullable();
                $table->integer('id_anaquel')->references('id')->on('anaqueles')->nullable();
                $table->integer('id_user_created')->references('id')->on('users')->nullable();
                $table->integer('id_user_update')->references('id')->on('users')->nullable();
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
