<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Room;
use App\User;

class RoomController extends Controller
{
	public function join($code)
	{
		// Find room
		$room = Room::where('code', $code)->firstOrFail();

		return $this->createUser($room);
	 }

	 private function createUser($room) {
	 	// Create user
		$user = new User();
		$user->room_id = $room->id;
		$user->save();

		return [
			'room_id' => $room->id,
			'user_id' => $user->id,
			'code' => $room->code,
			'destination' => $room->destination
		];
	 }

	public function create(Request $request) {
		// Create room
		$room = new Room();
		$room->destination = $request->destination;
		$room->code = str_random(6);
		$room->save();

		return $this->createUser($room);
	}

	public function places() {

	}
}
