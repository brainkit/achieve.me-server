<?php

class TypeTableSeeder extends Seeder {
    public function run(){
        DB::table('types')->delete();

        Type::create(array(
            'name' => "auto"
        ));

        Type::create(array(
            'name' => "all"
        ));

        Type::create(array(
            'name' => "daily"
        ));

        Type::create(array(
            'name' => "weekly"
        ));
    }
}