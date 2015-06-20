<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Achievement extends Eloquent {
    //public $timestamps = false;
    use SoftDeletingTrait;
    protected $table = 'achievements';
    protected $softDelete = true;

    public function types() {
        return $this ->belongsToMany('Type', 'achievement_types','achievement_id',
            'type_id');
    }
}