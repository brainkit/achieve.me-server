<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('hash');
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
        Schema::dropIfExists('user_settings');
        Schema::dropIfExists('user_subs');
        Schema::dropIfExists('user_urls');
        Schema::dropIfExists('user_achievements');
        //DB::statement('SET FOREIGN_KEY_CHECKS = 0'); // disable foreign key constraints
        Schema::dropIfExists('users',  function(Blueprint $table)
        {
            $table->dropUnique('users_email_unique');
        });
        //DB::statement('SET FOREIGN_KEY_CHECKS = 1'); // enable foreign key constraints
    }

}