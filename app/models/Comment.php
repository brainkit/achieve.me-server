<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Comment extends Eloquent{
    protected $table = "comments";
    protected $softDelete = true;
}