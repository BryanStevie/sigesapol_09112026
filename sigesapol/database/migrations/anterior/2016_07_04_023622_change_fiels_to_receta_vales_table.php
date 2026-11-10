<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeFielsToRecetaValesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('receta_vales', function (Blueprint $table) {
            //            
            $table->renameColumn('colegiatura_beneficiario', 'cip_beneficiario');
            $table->renameColumn('fecha_expedición', 'fecha_expedicion');
            $table->renameColumn('fecha_expiración', 'fecha_expiracion');
            $table->string('grado',100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('receta_vales', function (Blueprint $table) {
            //
            $table->renameColumn('cip_beneficiario', 'colegiatura_beneficiario');
            $table->renameColumn('fecha_expedicion', 'fecha_expedición');
            $table->renameColumn('fecha_expiracion', 'fecha_expiración');
        });
    }
}
