<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRegistroSanitarioAndLoteAndFechaVencimientoToTransferenciaProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transferencia_productos', function (Blueprint $table) {
			$table->string('registro_sanitario',20)->nullable();
			$table->string('lote',20)->nullable();
			$table->date('fecha_vencimiento')->nullable();
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
