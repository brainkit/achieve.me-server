<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAchievementProofsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('achievement_proofs', function (Blueprint $table){
			$table->increments('id');
			$table->unsignedInteger('achievement_id');
			$table->foreign('achievement_id')->references('id')->on('achievements');
			$table->text('description');
			$table->string('image')->nullable();
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
		Schema::dropIfExists('achievement_proofs', function(Blueprint $table) {
			$table->dropForeign('achievement_id_foreign');
		});
	}

}
