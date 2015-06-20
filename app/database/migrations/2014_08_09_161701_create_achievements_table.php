<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAchievementsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('achievements', function(Blueprint $table)
		{
            $table->increments('id');
            $table->unsignedInteger('parent_id')->nullable();
            $table->foreign('parent_id')->references('id')->on('achievements');
            //$table->unsignedInteger('type_id');
            //$table->foreign('type_id')->references('id')->on('achievement_types');
            $table->string('title');
            $table->text('description');
            $table->integer('points');
            //$table->smallInteger('status')->unsigned();
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
        Schema::dropIfExists('achievements', function(Blueprint $table){
            $table->dropForeign('achievements_parent_id_foreign');
            $table->dropForeign('user_achievements_achievement_id_foreign');

        });
	}

}
