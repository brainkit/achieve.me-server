<?php

class DatabaseSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        Eloquent::unguard();
        $this->call('UserTableSeeder');
        $this->call('TypeTableSeeder');
        $this->call('AchievementTableSeeder');
        $this->call('AchievementTypeTableSeeder');
        $this->call('AchievementVoiceTableSeeder');
    }

}
