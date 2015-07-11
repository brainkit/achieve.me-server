<?php

class AchievementVoiceTableSeeder extends Seeder {
    public function run(){
        Eloquent::unguard();
        DB::table('achievement_voices')->delete();

        $default_voice_id = 1;
        //$type = DB::table('achievements')->pluck('id');
        $achievement = DB::table('achievements')->first();
        $user = DB::table('users')->first();

        AchievementVoice::create(array(
                'achievement_id' => $achievement->id,
                'user_id' => $user->id,
                'voice' => true
        ));
    }
}