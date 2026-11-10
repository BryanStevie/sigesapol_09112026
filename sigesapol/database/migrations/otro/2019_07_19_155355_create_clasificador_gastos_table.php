<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClasificadorGastosTable extends Migration
{
    public function up()
    {
        Schema::create('clasificador_gastos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('descripcion_cg',20);            
            $table->integer('estado')->nullable();
            $table->integer('id_user_desactivate')->nullable();       
            $table->date('fec_desactivate')->nullable();       
            $table->nullableTimestamps();
        });
    }

    public function down()
    {
        Schema::drop('clasificador_gastos');
    }
}
