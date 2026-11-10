<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDependenciasTable extends Migration
{
    public function up()
    {
        Schema::create('dependencias', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre_dependencia',200);
            $table->string('opcion',1);
            $table->integer('id_user_desactivate')->nullable();       
            $table->date('fec_desactivate')->nullable();       
            $table->integer('estado')->nullable();
            $table->nullableTimestamps();
        });
    }

    public function down()
    {
        Schema::drop('dependencias');
    }
}
