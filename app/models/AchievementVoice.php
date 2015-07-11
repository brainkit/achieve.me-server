<?php
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class AchievementVoice extends Eloquent {
    //public $timestamps = false;
    use SoftDeletingTrait;


    protected $table = 'achievement_voices';
    protected $softDelete = true;
}