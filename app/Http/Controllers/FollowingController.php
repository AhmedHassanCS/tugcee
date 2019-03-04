<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Following;

class FollowingController extends Controller
{
    public function store($record)
    {
        Following::create($record);
    }
    public function getFollowers()
    {
        $user = $this->getUserFromToken();
        $user_id= $user['id'];

        $followers= Following::where('followed',$user_id)->get();

        $totalfollowers = array();
        foreach ($followers as $follower) {
            $detailed = app('App\Http\Controllers\usersController')->getUserById($follower['follower']);
            if($detailed!=[]){
                $obj = array(
                    'name' => $detailed[0]['name'],
                    'username' => $detailed[0]['username'],
                    'id'=>$detailed[0]['id']
                    );
                array_push($totalfollowers,$obj);
            }
        }
        return $totalfollowers;
    }
    public function getFollowings()
    {
        $user = $this->getUserFromToken();
        $user_id= $user['id'];

        $followings= Following::where('follower',$user_id)->get();

        $totalFollowings = array();
        foreach ($followings as $following) {
            $detailed = app('App\Http\Controllers\usersController')->getUserById($following['followed']);
            if($detailed!=[]){
                $obj = array(
                    'name' => $detailed[0]['name'],
                    'username' => $detailed[0]['username'],
                    'id'=>$detailed[0]['id']
                    );
                array_push($totalFollowings,$obj);
            }
        }
        return $totalFollowings;
    }
    
    public function removeFollower(Request $request)
    {
        $user = $this->getUserFromToken();
        $user_id= $user['id'];
        $follower_id = $request['id'];
        Following::where('followed',$user_id)->where('follower',$follower_id)->delete();
    }
    
    public function removeFollowed(Request $request)
    {
        $user = $this->getUserFromToken();
        $user_id= $user['id'];
        $followed_id = $request['id'];
        Following::where('follower',$user_id)->where('followed',$followed_id)->delete();
    }

    public function followingExists($follower,$followed)
    {
        return Following::where('follower',$follower)->where('followed',$followed)->exists();
    }
}
