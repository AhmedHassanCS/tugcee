<?php

namespace App\Http\Controllers;

use App\Request as req;
use Illuminate\Http\Request;
use Carbon\Carbon;
use usersController;
class RequestController extends Controller
{
    protected $table ="requests";
    public function sendRequest(Request $request)
    {
        try{
            $sender = $this->getUserFromToken();
            $sender_id= $sender['id'];
            $recipient_id=$request['id'];
            $current_time = Carbon::now()->toDayDateTimeString();

            if($sender_id==$recipient_id)
                return array('error' => "You can not follow yourself Bro!");
            elseif(req::where('sender',$sender_id)->where('recipient',$recipient_id)->exists())
                return array('error' => "Your requests already sent!");
            elseif(app('App\Http\Controllers\FollowingController')->followingExists($sender_id,$recipient_id))
                return array('error' => "You already following this person!");
            else
                req::create([
                    'sender'=>$sender_id,
                    'recipient'=>$recipient_id,
                    'date_time' =>$current_time
                    ]);
        }
        catch (Exception $e)
        {
            return array('error' =>  $e->getMessage());
        }
    }
    public function getRequests()
    {
        $owner= $this->getUserFromToken();
        $owner_id = $owner['id'];
        $requests= req::where('recipient',$owner_id)->get();

        $totalReqs = array();
        foreach ($requests as $req) {
            $user = app('App\Http\Controllers\usersController')->getUserById($req['sender']);
            if($user!=[]){
                $obj = array(
                    'name' => $user[0]['name'],
                    'username' => $user[0]['username'],
                    'id'=>$user[0]['id']
                    );
                array_push($totalReqs,$obj);
            }
        }
        return $totalReqs;
    }
    public function acceptRequest(Request $request)
    {
        try{
            //the current user is the one who recieved the request
            $recipient = $this->getUserFromToken();
            $recipient_id= $recipient['id'];
            //the id in the request is the id for the user who sent the following request
            $sender_id=$request['id'];

            if(req::where('sender',$sender_id)->where('recipient',$recipient_id)->exists())
            {
                req::where('sender',$sender_id)->where('recipient',$recipient_id)->delete();
                
                //the follower is whoever sent the request, the followed is the recipient
                $following_entry = array(
                    'follower' => $sender_id,
                    'followed' => $recipient_id);
                app('App\Http\Controllers\FollowingController')->store($following_entry);
                
                //the reciever of the notification is the sender of the request
                $recipientNameUser = $recipient['name'].' <'.$recipient['username'].'>';
                $notificationEntry= array(
                    'recipient'=>$sender_id,
                    'not_type'=>'request_approved',
                    'message'=>"$recipientNameUser Accepted your following request.");
                app('App\Http\Controllers\NotificationController')->setNotification($notificationEntry);
            }
            else return array('error' => 'No request exists');
        }

        catch (Exception $e)
        {
            return array('error' =>  $e->getMessage());
        }
    }
    public function rejectRequest(Request $request)
    {
        try{
            //the current user is the one who recieved the request
            $recipient = $this->getUserFromToken();
            $recipient_id= $recipient['id'];
            //the id in the request is the id for the user who sent the following request
            $sender_id=$request['id'];

            if(req::where('sender',$sender_id)->where('recipient',$recipient_id)->exists())
            {
                req::where('sender',$sender_id)->where('recipient',$recipient_id)->delete();
                
                //the reciever of the notification is the sender of the request
                $recipientNameUser = $recipient['name'].' <'.$recipient['username'].'>';
                $notificationEntry= array(
                    'recipient'=>$sender_id,
                    'not_type'=>'request_denied',
                    'message'=>"$recipientNameUser rejected your following request.");
                app('App\Http\Controllers\NotificationController')->setNotification($notificationEntry);
            }
            else return array('error' => 'No request exists');
        }

        catch (Exception $e)
        {
            return array('error' =>  $e->getMessage());
        }
    }

    public function cancelRequest(Request $request)
    {
        try{
            //the current user is the one who sent but want to cancel the request
            $recipient = $this->getUserFromToken();
            $sender_id= $recipient['id'];
            
            //the id in the request is the id for the user who must have recieved the following request
            $recipient_id= $request['id'];

            if(req::where('sender',$sender_id)->where('recipient',$recipient_id)->exists())
            {
                req::where('sender',$sender_id)->where('recipient',$recipient_id)->delete();
            }
            else return array('error' => 'No request exists');
        }

        catch (Exception $e)
        {
            return array('error' =>  $e->getMessage());
        }
    }

    //the requests current user made
    public function getMyRequests()
    {
        $owner= $this->getUserFromToken();
        $owner_id = $owner['id'];
        $requests= req::where('sender',$owner_id)->get();

        $totalReqs = array();
        foreach ($requests as $req) {
            $user = app('App\Http\Controllers\usersController')->getUserById($req['recipient']);
            if($user!=[]){
                $obj = array(
                    'name' => $user[0]['name'],
                    'username' => $user[0]['username'],
                    'id'=>$user[0]['id']
                    );
                array_push($totalReqs,$obj);
            }
        }
        return $totalReqs;
    }
}
