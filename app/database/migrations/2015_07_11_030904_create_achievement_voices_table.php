<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAchievementVoicesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('achievement_voices', function (Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedInteger('achievement_id');
            $table->foreign('achievement_id')->references('id')->on('achievements');
            $table->boolean('voice');
            $table->timestamps();
            $table->softDeletes();
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::dropIfExists('achievement_voices', function(Blueprint $table) {
            $table->dropForeign('achievement_id_foreign');
            $table->dropForeign('user_id_foreign');
        });
	}

}
