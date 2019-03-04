<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Validations;

class RegisterController extends Controller
{
    public function create(Request $user)
    {
        /*
        ** user data(username,phone,email,name,password)
        ** here some data is tested to be unique
        ** (username,phone,email)
        ** if the data is unique validate then create the user and return nothing
        ** if the data isn't unique return error in formate array("error" => "<value> exists")
        **/
        if(!isset($user["username"]) || empty($user["username"]))
            return array("error" => "Username is not set!");
        if(!isset($user["password"]) || empty($user["password"]))
            return array("error" => "Password is not set!");
        if(!isset($user["email"]) || empty($user["email"]))
            return array("error" => "Email is not set!");
        if(!isset($user["phone"]) || empty($user["phone"]))
            return array("error" => "Phone is not set!");
        if(!isset($user["name"]) || empty($user["name"]))
            return array("error" => "Name is not set!");

        //start validations//
        if(!Validations::validate_username($user["username"]))
            return array("error" => Validations::error());

        elseif(!Validations::validate_email($user["email"]))
            return array("error" => Validations::error());

        elseif(!Validations::validate_phone($user["phone"]))
            return array("error" => Validations::error());

        elseif(!Validations::validate_password($user["password"]))
            return array("error" => Validations::error());

        elseif(!Validations::validate_name($user["name"]))
            return array("error" => Validations::error());
        //end validations//

        //start checking existance//
        if (User::where('username', $user['username'])->exists()) 
             return array("error"=>"Username exists! try another one");
        
        elseif(User::where('email', $user['email'])->exists())
            return array('error' =>"Email exists! login or press forgot password");

        elseif(User::where('phone', $user['phone'])->exists())
            return array('error' =>"Phone exists! login or press forgot password");
        //end checking existance//

        //start storing//
        User::create([
            'username'=>$user['username'],
            'email'=>$user['email'],
            'phone'=>$user['phone'],
            'password'=> $user['password'],
            'name'=>$user['name']
            ]);

        $headers = ['Accept' => 'application/json'];
        return app('App\Http\Controllers\Auth\LoginController')->authenticate($user);
        //return response()->created();
        //end storing//
    }
}
