<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFielsToTransferenciasTable extends Migration
{
    
    public function up()
    {
        Schema::table('transferencias', function (Blueprint $table) {
            $table->integer('id_ipress')->nullable()->unsigned();            
        });
    }
    
    public function down()
    {
        Schema::table('transferencias', function (Blueprint $table) {
            //
        });
    }
}
