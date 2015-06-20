<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAchievementTypesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('achievement_types', function(Blueprint $table)
		{
            $table->increments('id');
            $table->unsignedInteger('type_id');
            $table->foreign('type_id')->references('id')->on('types');
            $table->unsignedInteger('achievement_id');
            $table->foreign('achievement_id')->references('id')->on('achievements');
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
        Schema::dropIfExists('achievement_types', function(Blueprint $table) {
            $table->dropForeign('achievement_id_foreign');
            $table->dropForeign('type_id_foreign');
        });
	}

}
