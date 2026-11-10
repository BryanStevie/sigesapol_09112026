<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecetaAtendidasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receta_atendidas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nro_historia',25)->nullable();
            $table->string('serie',10)->nullable();
            $table->string('nro_ticket',15)->nullable();
            $table->string('dni_afiliado',8)->nullable();
            $table->string('nombre_afiliado',100)->nullable();
            $table->string('paterno_afiliado',100)->nullable();
            $table->string('materno_afiliado',100)->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->integer('edad')->nullable();
            $table->string('sexo',1)->nullable();
            $table->string('telefono',9)->nullable();
            $table->string('email',80)->nullable();
            $table->text('direccion')->nullable();
            $table->string('distrito')->nullable();
            $table->string('ubigeo',6)->nullable();
            $table->string('forpago',4)->nullable();
            $table->string('cmp',15)->nullable();
            $table->string('nombre_medico',100)->nullable();
            $table->string('paterno_medico',100)->nullable();
            $table->string('materno_medico',100)->nullable();
            $table->string('cod_consultorio',25)->nullable();
            $table->string('consultorio',255)->nullable();
            $table->string('cod_producto',25)->nullable();
            $table->string('producto',255)->nullable();
            $table->integer('id_user')->nullable()->unsigned();
            $table->foreign('id_user')->references('id')->on('users');
            $table->integer('id_farmacia')->nullable()->unsigned();
            $table->foreign('id_farmacia')->references('id')->on('farmacias');
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
        Schema::drop('receta_atendidas');
    }
}
