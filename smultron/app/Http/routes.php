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

Route::get('/', function () {
    return view('welcome');
});

<<<<<<< HEAD

Route::resource('room', 'RoomController');

Route::post('swipe', 'SwipeController@register');
=======
Route::post('room', 'RoomController@create');
Route::get('room/{code}', 'RoomController@join');
>>>>>>> 2272054299aa85d2622cef4d78384c0a2c9566b8
