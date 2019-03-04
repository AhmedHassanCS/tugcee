<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    public $timestamps = false;
    protected $table ="notifications";
    protected $fillable = ['recipient','not_type','message'];
    protected $primaryKey='not_id'; 
}
