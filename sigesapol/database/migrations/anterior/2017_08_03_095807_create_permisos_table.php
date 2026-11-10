<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermisosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permisos', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('idmenu')->unsigned();
			$table->string('crear', 1);
			$table->string('modificar', 1);
			$table->string('eliminar', 1);
			$table->string('lectura', 1);
			$table->timestamps();
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
