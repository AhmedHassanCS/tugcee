<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $table ="locations";
    public $timestamps = false;
    protected $fillable=['user_id','longitude','latitude','last_update'];
    protected $primaryKey='user_id'; 
}
