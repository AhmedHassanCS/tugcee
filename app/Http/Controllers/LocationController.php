<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Location;
use Carbon\Carbon;

class LocationController extends Controller
{

    public function getLocation(Request $request)
    {
         $user = $this->getUserFromToken();
        $user_id= $user['id'];
        $requested_id = $request['id'];
        if(!app('App\Http\Controllers\FollowingController')->followingExists($user_id,$requested_id))
            return array('error' => 'You are not following this person');
        else
        {
            $loc=  Location::where('user_id',$requested_id)->first();
            return array(
                'longitude' => $loc['longitude'],
                'latitude' => $loc['latitude'],
                'last_update' => $loc['last_update']
             );
        }
    }

    public function setLocation(Request $request)
    {
        $user = $this->getUserFromToken();
        $user_id= $user['id'];

        $longitude = $request['longitude'];
        $latitude = $request['latitude'];
        $current_time = Carbon::now()->toDayDateTimeString();

        if(Location::where('user_id',$user_id)->exists())
        {
            Location::where('user_id',$user_id)->update([
                'longitude'=>$longitude,
                'latitude' =>$latitude,
                'last_update'=>$current_time
                ]);
        }
        else
            Location::create([
                'user_id'=>$user_id,
                'longitude'=>$longitude,
                'latitude' =>$latitude,
                'last_update'=>$current_time
                ]); 
    }
}
