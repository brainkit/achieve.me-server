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
        /*
         * Сейчас + 24 часа
         */
        $default_time_limit = time() + (24 * 3600);
        $default_time_limit1 = date('Y-m-d H:i:s', $default_time_limit);
        Achievement::create(array(
            "parent_id" => null,
            "title" => "Зима не будет!",
            "description" => "Не заметить, как пришла зима",
            "rate" => $default_rate,
            "time_limit" => $default_time_limit1,
        ));
        /*AchievementType::create(array(
            "achievement_id" => '1',
            'type_id' => $default_type_id
        ));*/

        Achievement::create(array(
            "parent_id" => null,
            "title" => "Небеса подождут",
            "description" => "Небеса подождут",
            "rate" => $default_rate,
            "time_limit" => $default_time_limit1,
        ));
    }
}