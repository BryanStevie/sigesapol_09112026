<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAuditoriaProductoAnaquelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('producto_anaqueles', function (Blueprint $table) {
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
