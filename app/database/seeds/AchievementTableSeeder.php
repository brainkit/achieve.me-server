<?php
/**
 * Created by PhpStorm.
 * User: Julia
 * Date: 20.08.14
 * Time: 23:27
 */

class AchievementTableSeeder extends Seeder {
    public function run(){
        DB::table('achievements')->delete();

        $default_rate = 0;
        $default_status = 1; // new achivements
        $default_time_limit = time() + (24 * 36000); // Сейчас + 24 часа
        Achievement::create(array(
            "parent_id" => null,
            "title" => "Зима не будет!",
            "description" => "Не заметить, как пришла зима",
            "points" => $default_rate,
            "time_limit" => $default_time_limit,
        ));
        /*AchievementType::create(array(
            "achievement_id" => '1',
            'type_id' => $default_type_id
        ));*/

        Achievement::create(array(
            "parent_id" => null,
            "title" => "Небеса подождут",
            "description" => "Небеса подождут",
            "points" => $default_rate,
            "time_limit" => $default_time_limit,
        ));
    }
}