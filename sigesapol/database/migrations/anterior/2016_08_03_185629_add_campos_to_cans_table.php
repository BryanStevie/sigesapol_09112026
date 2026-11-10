<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCamposToCansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cans', function (Blueprint $table) {
            //
            $table->integer('id_establecimiento')->nullable()->unsigned();
            $table->foreign('id_establecimiento')->references('id')->on('establecimientos');
            $table->integer('cantidad')->nullable();
            $table->integer('ano')->nullable();
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
        Schema::table('cans', function (Blueprint $table) {
            //
        });
    }
}
