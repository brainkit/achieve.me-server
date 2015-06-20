<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Type extends Eloquent {
    //public $timestamps = false;
    use SoftDeletingTrait;


    protected $table = 'types';
    protected $softDelete = true;

    public function achievements() {
        return $this ->belongsToMany('Achievement', 'achievement_types','type_id',
        'achievement_id');
    }
}