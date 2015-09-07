<?php

class AchievementProof extends Eloquent {
    //public $timestamps = false;
    use SoftDeletingTrait;


    protected $table = 'achievement_proofs';
    protected $softDelete = true;
}