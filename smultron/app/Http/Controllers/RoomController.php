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
	private $gmaps_api_key = 'AIzaSyBspjy1O7ckHFWUhLC9yu1XNUVuzrveA0s';

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

	/**
	 * Create room
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function create(Request $request) {
		$room = new Room();
		$room->destination = $request->destination;
		$room->code = str_random(6);
		$room->save();

		return [
			'user' => $this->createUser($room),
			'places' => $this->fetchLocationData($request->destination, $room->id)
		];
		
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


	/**
	 * Fetch places from destination name
	 * @param  [type] $destination [description]
	 * @param  [type] $room_id     [description]
	 * @return [type]              [description]
	 */
    public function fetchLocationData($destination, $room_id) {

    	$destination = urlencode($destination);

        $location_data = file_get_contents(
            'https://maps.googleapis.com/maps/api/place/textsearch/json?query=' . $destination. '&key=' . $this->gmaps_api_key
        );

        if(empty($location_data)) {
            return response()->json(['ok' => false]);
        }

        $location_data = json_decode($location_data);


        $location_lat = $location_data->results[0]->geometry->location->lat;
        $location_lng = $location_data->results[0]->geometry->location->lng;


        $places_data = file_get_contents(
            'https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=' . $location_lat . ',' . $location_lng . 
            '&type=museum&radius=10000&key=' . $this->gmaps_api_key
        );

        $places_data = json_decode($places_data);

        foreach($places_data->results as $place_data) {

        	//Get image
        	
        	/*$image_data = file_get_contents(
        		'https://maps.googleapis.com/maps/api/place/photo?photo_reference=' . $place_data->photos[0]->photo_reference . '&maxwidth=1000&key=' . $this->gmaps_api_key
        	); */

        	$image = '';
        	if(isset($place_data->photos)) {
        		$image = 'https://maps.googleapis.com/maps/api/place/photo?photo_reference=' . $place_data->photos[0]->photo_reference . '&maxwidth=1000&key=' . $this->gmaps_api_key;
        	}

            $place = new Place;
            $place->title = $place_data->name;
            $place->category = serialize($place_data->types);
            $place->image = $image;
            $place->address = $place_data->vicinity;
            $place->room_id = $room_id;
            $place->save();
        }

        return Place::where('room_id', $room_id)->get();
    }   
}
