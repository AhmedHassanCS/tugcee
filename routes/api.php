<?php

use Illuminate\Http\Request;

$api= app('Dingo\Api\Routing\Router');


$api->version('v1',function($api)
{
    //1- registeration requires 5 inputs(username,name,password,email,phone)
    $api->post('register','App\Http\Controllers\Auth\RegisterController@create');
    //2- login requires 2 inputs(username,password)
    $api->post('login','App\Http\Controllers\Auth\LoginController@authenticate');

});

$api->version('v1',['middleware'=>'api.auth'],function($api)
{
    //3- user logout
    $api->post('logout','App\Http\Controllers\Auth\LoginController@logout');

    //4- search for another user
    $api->post('search','App\Http\Controllers\usersController@search');

    //5- make sure there is no following already exist then store
    $api->post('sendRequest','App\Http\Controllers\RequestController@sendRequest');
    //6- Get user requests from token
    $api->post('getRequests','App\Http\Controllers\RequestController@getRequests');
    //7- make sure there is a request -> delete request & set following record & set notification
    $api->post('acceptRequest','App\Http\Controllers\RequestController@acceptRequest');
    //8- make sure there is a request -> delete request & set notification
    $api->post('rejectRequest','App\Http\Controllers\RequestController@rejectRequest');

    //9- return all user's notifications
    $api->post('getNotifications','App\Http\Controllers\NotificationController@getNotifications');

    //10- get following people
    $api->post('getFollowings','App\Http\Controllers\FollowingController@getFollowings');
    //11- get followed people
    $api->post('getFollowers','App\Http\Controllers\FollowingController@getFollowers');

    //12- remove follower
    $api->post('removeFollower','App\Http\Controllers\FollowingController@removeFollower');
    //13- remove following
    $api->post('removeFollowed','App\Http\Controllers\FollowingController@removeFollowed');

    //14- update last location
    $api->post('setLocation','App\Http\Controllers\LocationController@setLocation');

    //15- make sure the requesting user is following requested -> return location
    $api->post('getLocation','App\Http\Controllers\LocationController@getLocation');

    //16- update last location
    $api->post('addPlace','App\Http\Controllers\FavLocationController@addPlace');

    //17- get account info
    $api->post('getInfo','App\Http\Controllers\usersController@getInfo');
    //18- edit account info
    $api->post('editInfo','App\Http\Controllers\usersController@editInfo');

    //19- get another user info
    $api->post('getUserInfo','App\Http\Controllers\usersController@getUserInfo');

    //20- Get requests this user made
    $api->post('getMyRequests','App\Http\Controllers\RequestController@getMyRequests');

    //21- Get requests this user made
    $api->post('cancelRequest','App\Http\Controllers\RequestController@cancelRequest');
    //22- change user's avatar
    $api->post('changeImg','App\Http\Controllers\usersController@changeImg');
    //23- change user's avatar
    $api->post('getImg','App\Http\Controllers\usersController@getImg');
    //24 - reset request
    $api->post('forget','App\Http\Controllers\usersController@Forget');


});
