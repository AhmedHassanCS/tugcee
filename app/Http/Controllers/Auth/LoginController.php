<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller
{   
    public function authenticate(Request $request)
    {
        if(!isset($request["username"]) || empty($request["username"]))
            return array("error" => "Username is missing!");
        if(!isset($request["password"]) || empty($request["password"]))
            return array("error" => "Password is missing!");

        $credentials = $request->only('username','password');
        try
        {
            if(! $token=JWTAuth::attempt($credentials))
            {
                return response()->json(['error' => 'Wrong username or password!'], 401);
            }
        }
        catch(JWTException $ex)
        {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        return response()->json(compact('token'));
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
    }

    public function show()
    {
        return User::all();
    }
}
