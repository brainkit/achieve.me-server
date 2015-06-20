<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class AchievementType extends Eloquent {
    //public $timestamps = false;
    use SoftDeletingTrait;


    protected $table = 'achievement_types';
    protected $softDelete = true;
}