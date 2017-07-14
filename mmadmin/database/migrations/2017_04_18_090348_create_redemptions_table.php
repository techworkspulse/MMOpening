<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRedemptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('redemptions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idCategory')->unsigned();
			$table->foreign('idCategory')->references('id')->on('categories');
            $table->integer('idUser')->unsigned();
			$table->foreign('idUser')->references('id')->on('users');
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
    	Schema::table('redemptions', function (Blueprint $table) {
    		$table->dropForeign(['idCategory']);
    		$table->dropForeign(['idUser']);
		});
		
        Schema::dropIfExists('redemptions');
    }
}
