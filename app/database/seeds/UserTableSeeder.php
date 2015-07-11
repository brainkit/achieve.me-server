<?php

class UserTableSeeder extends Seeder {

    public function run()
    {
        Eloquent::unguard();
        DB::table('users')->delete();

        User::create(array(
            'email' => 'syrex88@gmail.com',
            'password' => Hash::make('ghbywbg'),
            'hash' => Hash::make('1')
        ));

        User::create(array(
            'email' => 'seconduser',
            'password' => Hash::make('second_password'),
            'hash' => Hash::make('2')
        ));

        User::create(array(
            'email' => 'testuser',
            'password' => Hash::make('test_password'),
            'hash' =>Hash::make('3'),
        ));
    }

}