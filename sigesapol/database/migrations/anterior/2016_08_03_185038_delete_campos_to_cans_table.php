<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteCamposToCansTable extends Migration
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
            $table->dropColumn('id_farmacia');
            $table->dropColumn('id_producto');
            $table->dropColumn('cantidad');
            $table->dropColumn('ano');
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
