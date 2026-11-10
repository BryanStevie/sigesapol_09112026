<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAuditoriaMedicosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('medicos', function (Blueprint $table) {
                $table->integer('id_user_created')->references('id')->on('users')->nullable();
                $table->integer('id_user_update')->references('id')->on('users')->nullable();
                $table->integer('id_user_activate')->references('id')->on('users')->nullable();
                $table->timestamp('fec_activate')->nullable();
                $table->integer('id_user_desactivate')->references('id')->on('users')->nullable();
                $table->timestamp('fec_desactivate')->nullable();
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
