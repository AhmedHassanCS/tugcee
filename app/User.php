<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    public $timestamps = false;
    protected $fillable = ['username','password','name','phone','email','gender','age','address','enabled'];

    protected $hidden =['password','hash'];

    public function setPasswordAttribute($value) {
        $this->attributes['password'] = \Hash::make($value);
    }
}
