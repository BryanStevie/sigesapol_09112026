<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBeneficiariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('beneficiarios', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nompaisdelafiliado',3)->nullable();  
            $table->string('nomtipdocafiliado',20)->nullable();
            $table->string('nrodocafiliado',10)->nullable();
            $table->string('apepatafiliado',50)->nullable();
            $table->string('apematafiliado',50)->nullable();
            $table->string('apecasafiliado',50)->nullable();
            $table->string('nomafiliado',50)->nullable();
            $table->string('fecnacafiliado',10)->nullable();     
            $table->integer('edadafiliado')->nullable();   
            $table->string('nomsexo',2)->nullable();   
            $table->string('estado',10)->nullable();     
            $table->string('parentesco',20)->nullable();
            $table->string('nomtipdoctitular',20)->nullable();
            $table->string('nrodoctitular',10)->nullable();
            $table->string('apepattitular',50)->nullable();
            $table->string('apemattitular',50)->nullable();
            $table->string('apecastitular',50)->nullable();  
            $table->string('nomtitular',50)->nullable();  
            $table->string('cip',8)->nullable();
            $table->string('ubigeo',6)->nullable();
            $table->string('grado',50)->nullable();
            $table->string('situacion',50)->nullable();
            $table->string('caducidad',10)->nullable();
            $table->integer('discapacidad')->nullable();
            $table->string('dni_titular',10)->nullable();
            $table->string('id_excel',30)->nullable();
            $table->string('fecha_atencion',30)->nullable();
            $table->string('fecha_alta',30)->nullable();
            $table->string('observacion_original',500)->nullable();
            $table->string('mes',2)->nullable();
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
        Schema::drop('beneficiarios');
    }
}
