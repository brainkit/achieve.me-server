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

        $default_points = 10;
        $default_status = 1; // new achivements
        Achievement::create(array(
            "parent_id" => null,
            "title" => "Зима не будет!",
            "description" => "Не заметить, как пришла зима",
            "points" => $default_points
        ));
        /*AchievementType::create(array(
            "achievement_id" => '1',
            'type_id' => $default_type_id
        ));*/

        Achievement::create(array(
            "parent_id" => null,
            "title" => "Небеса подождут",
            "description" => "Небеса подождут",
            "points" => $default_points,
        ));
    }
}