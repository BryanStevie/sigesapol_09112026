<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProveedoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proveedores', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ruc_proveedor',11);  
            $table->string('nombre_proveedor',100);
            $table->string('direccion_proveedor',100)->nullable();
            $table->string('telefono_proveedor',15)->nullable();
            $table->string('fax_proveedor',15)->nullable();
            $table->string('email_proveedor',30)->nullable();
            $table->integer('estado')->nullable();
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
        Schema::drop('proveedores');
    }
}
