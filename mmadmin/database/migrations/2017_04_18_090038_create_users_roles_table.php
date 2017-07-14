<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_roles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idUser')->unsigned();
			$table->foreign('idUser')->references('id')->on('users');
            $table->integer('idRole')->unsigned();
			$table->foreign('idRole')->references('id')->on('roles');
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
    	Schema::table('users_roles', function (Blueprint $table) {
    		$table->dropForeign(['idUser']);
			$table->dropForeign(['idRole']);
		});
		
        Schema::dropIfExists('users_roles');
    }
}
