<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecetaValesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('receta_vales', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_farmacia')->nullable()->unsigned();
            $table->foreign('id_farmacia')->references('id')->on('farmacias');
            $table->integer('id_user')->nullable()->unsigned();
            $table->foreign('id_user')->references('id')->on('users');
            $table->integer('id_consultorio')->nullable()->unsigned();
            $table->foreign('id_consultorio')->references('id')->on('consultorios');
            $table->string('nro_receta', 10)->nullable();
            $table->string('dni_beneficiario', 8)->nullable();
            $table->string('nombre_beneficiario', 100)->nullable();
            $table->string('paterno_beneficiario', 100)->nullable();
            $table->string('materno_beneficiario', 100)->nullable();
            $table->string('colegiatura_beneficiario', 12)->nullable();
            $table->string('tps_beneficiario', 12)->nullable();
            $table->string('tipo_beneficiario', 20)->nullable();
            $table->date('fecha_expedición')->nullable();
            $table->date('fecha_expiración')->nullable();
            $table->date('fecha_registro')->nullable();
            $table->nullableTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('receta_vales');
    }

}
