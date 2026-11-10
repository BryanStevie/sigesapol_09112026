<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImportacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('importaciones', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_user')->nullable()->unsigned();
            $table->foreign('id_user')->references('id')->on('users');
            $table->integer('id_establecimiento')->nullable()->unsigned();
            $table->foreign('id_establecimiento')->references('id')->on('establecimientos');
            $table->string('url_data',255)->nullable();
            $table->date('fecha_importacion')->nullable();
            $table->string('tabla',50)->nullable();
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
        Schema::drop('importaciones');
    }
}
