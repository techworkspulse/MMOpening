<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_details', function (Blueprint $table) {
            $table->integer('idUser')->unsigned();
			$table->foreign('idUser')->references('id')->on('users');
            $table->string('IC/Passport', '15')->unique();
            $table->string('PhoneNumber', '15')->unique();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    	Schema::table('users_details', function (Blueprint $table) {
    		$table->dropForeign(['idUser']);
		});
		
        Schema::dropIfExists('users_details');
    }
}
