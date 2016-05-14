<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Room;
use App\User;
use App\Swipe;
use App\Place;

class RoomController extends Controller
{
	/**
	 * Join a room based on entered code
	 * @param  [type] $code [description]
	 * @return [type]       [description]
	 */
	public function join(Request $request)
	{
		$room = Room::where('code', $request->code)->first();

		if(!$room) {
			return response()->json(['ok' => false]);
		}

		return $this->createUser($room);
	}

	/**
	 * Creates a new anonymous user
	 * @param  [type] $room [description]
	 * @return [type]       [description]
	 */
	private function createUser($room) {
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

	public function matches($room_id) {
		
   		//Get amount of users in room
   		$room_user_count = User::where('room_id', $room_id)->count();

		$matches = Swipe::select('place_id')
			->groupBy('place_id', 'state')
			->having('state', '=', 1)
			->havingRaw('count(*) = ' . $room_user_count)
			->get();


		$places = Place::whereIn('id', $matches)->get();

    	return $places;
	}
}
