<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTopicoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('topicos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre',100)->nullable();
			$table->integer('id_establecimiento')->nullable()->unsigned();
            $table->foreign('id_establecimiento')->references('id')->on('establecimientos');
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
