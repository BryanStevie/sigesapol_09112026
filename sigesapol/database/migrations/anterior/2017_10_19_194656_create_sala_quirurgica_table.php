<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalaQuirurgicaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sala_quirurgicas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre',100)->nullable();
			$table->integer('id_establecimiento')->nullable()->unsigned();
            $table->foreign('id_establecimiento')->references('id')->on('establecimientos');
			$table->string('estado',1)->nullable();
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
        //
    }
}
