<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notification;

class NotificationController extends Controller
{
    public function setNotification($record)
    {
        Notification::create($record);
    }
    public function getNotifications()
    {
        $user= $this->getUserFromToken();
        $user_id=$user['id'];
        $nots= Notification::where('recipient',$user_id)->get();
        //return $nots;
        $totalnots = array();
        foreach ($nots as $not) {
                $obj = array(
                    'not_type' => $not['not_type'],
                    'message' => $not['message']
                    );
                array_push($totalnots,$obj);
        }
        return $totalnots;

    }
}
