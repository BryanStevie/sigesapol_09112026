<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBeneficiarios2TodoTable extends Migration
{
    public function up()
    {
        Schema::create('beneficiarios2_todo', function (Blueprint $table) {
            $table->increments('id');            
            $table->string('nrodoc',15)->nullable();
            $table->string('tipo_doc',2)->nullable();
            $table->string('observacion_original',500)->nullable();
            $table->string('fecha_atencion',10)->nullable();
            $table->string('fecha_alta',10)->nullable();
            $table->string('mes',2)->nullable();
            $table->string('id_excel',30)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('beneficiarios2_todo');
    }
}
