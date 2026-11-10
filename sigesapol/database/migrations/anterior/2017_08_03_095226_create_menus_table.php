<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('nombremenu', 200);
			$table->integer('parentid')->unsigned();
			$table->string('url character', 200);
			$table->string('flagregistro', 1);
			$table->string('icono', 300);
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
