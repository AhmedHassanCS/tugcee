<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Following extends Model
{
    public $timestamps = false;
    protected $table = 'followings';
    protected $fillable =['follower','followed'];
    protected $primaryKey='follower'; 
}
