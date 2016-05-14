<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

//Just to have something at the start
Route::get('/', function () {
    return view('welcome');
});

// Guest routes
Route::post('room', 'RoomController@create');
Route::post('room/join', 'RoomController@join');

// User routes
Route::post('swipe', 'SwipeController@register');
Route::get('room/{room_id}/{user_id}/next', 'SwipeController@getNextPlace');
Route::get('room/{room_id}', 'RoomController@matches');