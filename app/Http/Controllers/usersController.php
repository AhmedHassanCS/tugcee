<?php

namespace App\Http\Controllers;
use App\User;
use App\Following;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Validation\Rule;
use  Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\Forget;


class usersController extends Controller
{
    public function search(Request $request)
    {
        if(!isset($request['search_by']) || !isset($request['value']))
        {
            return array('error'=>'data missing');
        }
        else
        {
            $this_user = JWTAuth::parseToken()->authenticate();
            $this_user_id = $this_user['id'];
            $search_by=$request['search_by'];
            $value=$request['value'];

            //start query
            $matches = User::whereNotIn('id', function($query) use($this_user_id){
                    $query->select('followed')
                    ->from(with(new Following)->getTable())
                    ->where('follower', $this_user_id);

                })->whereNotIn('id', function($query) use($this_user_id){
                    $query->select('recipient')
                    ->from('requests')
                    ->where('sender', $this_user_id);

                })->where($search_by,'LIKE',"%$value%")->get();
            //end query
            $returns =array();
            foreach ($matches as $match) {
                if($match['id']!=$this_user_id){
                    $obj = array(
                        'name' => $match['name'],
                        'username' => $match['username'],
                        'id'=>$match['id'],
                        'img_path' => (base_path() .'/'. $match['img_path'])
                        );
                    array_push($returns,$obj);
                }
            }
            return $returns;
        }
    }
    public function getUserById($id)
    {
        return User::where('id',$id)->get();
    }
    public function getUserInfo(Request $req)
    {
        $id=$req['id'];
        return User::where('id',$id)->get()[0];

    }
    public function getInfo()
    {
        return $this->getUserFromToken();
    }

    public function editInfo(Request $request)
    {
        $user = $this->getUserFromToken();
        $user_id = $user['id'];
        $attrib = $request['attrib'];
        $newVal = $request['newVal'];
        if($attrib=='username' || $attrib=='email' || $attrib=='phone')
        {
            if(User::where($attrib,$newVal)->exists())
                return array('error' => "$attrib already exist!" );
        }
        else if($attrib=='password' || $attrib=='hash')
            return array('error' => "Access denied!" );
        else
            User::where('id',$user_id)->update([$attrib => $newVal]);
    }
    public function changeImg(Request $request)
    {
       $user = $this->getUserFromToken();
       $user_id = $user['id'];
       $validator = Validator::make($request->all(), [
       'avatar' => 'image|required',
       ]);
       if ($validator->fails()){
          return array('error' => "Content must be image" );
       }
       $user =  User::where('id',$user_id)->first();
       $path = $request->file('avatar')->store('avatars');
       Storage::delete($user->img_path);
       $user->img_path = $path;
       $user->save();
       return  base_path() .'/'. $path;

    }
    public function getImg()
    {
       $user = $this->getUserFromToken();
       $user_id = $user['id'];
       $user = User::where('id',$user_id)->first();
       return  base_path() .'/'. $user->img_path;
    }
    public function Forget(Request $request)
    {
       //$user = $this->getUserFromToken();
       //$user_id = $user['id'];
       $user = User::where('email', $request->email)->firstOrFail();
       $reset = rand(1111,9999);
       $user->reset = $reset;
        $user->save();
       Mail::to($user->email)->send(new Forget($user->name , $reset));
       return array('status' => 'message sent to your mail' );
    }
}
