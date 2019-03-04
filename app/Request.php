<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    public $timestamps = false;
    protected $table ="requests";
    protected $fillable=['sender','recipient','date_time'];
    protected $primaryKey='sender'; 
}
