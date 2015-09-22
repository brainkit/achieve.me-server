<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Comment extends Eloquent{
    protected $table = "payments";
    protected $softDelete = true;

    public function user()
    {
        return $this->belongsTo('User');
    }

    public function achievement()
    {
        return $this->belongsTo('Achievement');
    }
}