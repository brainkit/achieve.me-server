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

    public function voices() {
        return $this->belongsToMany('AchievementVoice', 'achievements_voices', 'achievement_id', 'voice_id');
    }

    public function proofs() {
        return $this->belongsToMany('AchievementProof', 'achievements_proofs', 'achievement_id', 'proof_id');
    }
}