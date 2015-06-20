<?php

class AchievementTypeTableSeeder extends Seeder {
    public function run(){
        DB::table('achievement_types')->delete();

        $default_type_id = 1;
        $type = DB::table('types')->where('name', 'all')->pluck('id');
        $achievements = DB::table('achievements')->get();
        //$type = Type::where('name','=','all')->first()->get;
        foreach($achievements as $achieve) {
            $achieve_id = $achieve->id;
            AchievementType::create(array(
                "achievement_id" => $achieve_id,
                'type_id' => $type
            ));
        }

    }
}