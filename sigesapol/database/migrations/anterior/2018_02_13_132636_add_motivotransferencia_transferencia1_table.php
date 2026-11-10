<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMotivotransferenciaTransferencia1Table extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('transferencias', function (Blueprint $table) {
            //
            $table->integer('id_motitransferencia')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('transferencias', function (Blueprint $table) {
            //
        });
    }

}
