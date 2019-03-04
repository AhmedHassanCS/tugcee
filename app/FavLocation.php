<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FavLocation extends Model
{
    protected $table ="fav_locations";
    public $timestamps = false;
    protected $fillable=['user_id','longitude','latitude','label','description'];
    protected $primaryKey='floc_id';
}
