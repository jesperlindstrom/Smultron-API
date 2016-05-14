<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Room;

class RoomController extends Controller
{
	public function join($code)
	{
		$room = Room::where('code', $code)->firstOrFail();
		$user = new User();
		$user->room_id = $room->id;
		$user->save();

		return [
			'room_id' => $room->id;
			'user_id' => $user->id;
		];
	 }

	public function create() {
		// Create room
		$room = new Room();
		$room->destination = Input::get('destination');
		$room->save();

		// Create user
		$user = new User();
		$user->room_id = $room->id;
		$user->save();

		return [
			'room_id' => $room->id;
			'user_id' => $user->id;
		];
	}
}
