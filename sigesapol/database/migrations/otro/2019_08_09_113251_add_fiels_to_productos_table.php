<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFielsToProductosTable extends Migration
{
    
    public function up()
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->string('codigo_siga',20)->nullable();
            $table->string('concentracion',50)->nullable();
            $table->string('form_farm',50)->nullable();
            $table->string('presentacion',50)->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('productos', function (Blueprint $table) {
            //
        });
    }
}
