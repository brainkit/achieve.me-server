<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

    use UserTrait,
        RemindableTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';
    protected $softDelete = true;

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array('password', 'remember_token');

    public function achievements() {
        return $this->belongsToMany('Achievement', 'user_achievements', 'user_id', 'achievement_id');
    }

    public function subs() {
        return $this->belongsToMany('UserSettings', 'user_subs', 'user_id', 'user_id_sub');
    }

    public function voices() {
        return $this->belongsToMany('AchievementVoice', 'achievements_voices', 'user_id', 'voice_id');
    }
}
