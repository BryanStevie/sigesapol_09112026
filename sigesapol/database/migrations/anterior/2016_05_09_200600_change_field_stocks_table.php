<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeFieldStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stocks', function (Blueprint $table) {
            //
            $table->integer('monto_inicial')->nullable()->change();
            $table->integer('monto_actual')->nullable()->change();
            $table->integer('monto_consumido')->nullable()->change();
            $table->date('fecha_registro')->nullable()->change();
            $table->smallInteger('estado')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stocks', function (Blueprint $table) {
            //
        });
    }
}
