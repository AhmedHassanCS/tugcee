<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FavLocation;

class FavLocationController extends Controller
{
    public function addPlace(Request $request)
    {
        $user = $this->getUserFromToken();
        $user_id= $user['id'];
        $label = $request['label'];
        $longitude= $request['longitude'];
        $latitude= $request['latitude'];

        if(isset($request['description']))
            $description= $request['description'];
        else $description = null;

        FavLocation::create([
            'user_id'=> $user_id,
            'label'=> $label,
            'longitude'=> $longitude,
            'latitude'=> $latitude,
            'description'=> $description
            ]);

    }
}
