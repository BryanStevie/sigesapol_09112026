<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBeneficiarios2Table extends Migration
{
    public function up()
    {
        Schema::create('beneficiarios2', function (Blueprint $table) {
            $table->increments('id');            
            $table->string('nrodoc',9)->nullable();
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('beneficiarios2');
    }
}
