<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRedemptionStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('redemption_statuses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idRedemption')->unsigned();
			$table->foreign('idRedemption')->references('id')->on('redemptions');
            $table->boolean('isRedeemed');
            $table->integer('idAdmin')->unsigned();
			$table->foreign('idAdmin')->references('id')->on('users');
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
    	Schema::table('redemption_statuses', function (Blueprint $table) {
    		$table->dropForeign(['idRedemption']);
    		$table->dropForeign(['idAdmin']);
		});
		
        Schema::dropIfExists('redemption_statuses');
    }
}
