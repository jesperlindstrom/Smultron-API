<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Swipe;
use App\Place;
use App\User;

use DB;

class SwipeController extends Controller
{
	/**
	 * Register swipe
	 * @param  Request $request POST-data
	 * @return json           Return ok
	 */
   public function register(Request $request) {
   		$swipe = new Swipe;
   		$swipe->user_id = $request->user_id;
   		$swipe->place_id = $request->place_id;
   		$swipe->state = $request->state;
   		$swipe->save();

   		//Hämta användarens rum
      	$user = User::findOrFail($swipe->user_id);
      	$room_id = $user->room_id;

        return [
            'matches' => $this->getMatchesCount($room_id),
           	'next' => $this->getNextPlace($swipe->user_id, $room_id)
        ];
   }

   /**
    * Get number of matches
    * @param  int $room_id ID for the room
    * @return int          Match count
    */
   private function MatchesCount($room_id) {
   		//Get amount of users in room
   		$room_user_count = User::where('room_id', $room_id)->count();

    	$matches_count = Swipe::select('place_id')->groupBy('place_id', 'state')->having('state', '=', 1)->havingRaw('count(*) = ' . $room_user_count)->get()->count();

    	return $matches_count;
   }

   public function getNextPlace($user_id, $room_id) {
      //Hämta swipes
      $swipes = Swipe::where('user_id', $user_id)->get();

      $swiped_places = [];
      foreach($swipes as $swipe) {
      	$swiped_places[] = $swipe->place_id;
      }

      //Filtrera baserat på swipes...
      $places = Place::where('room_id', $room_id)->whereNotIn('id', $swiped_places)->firstOrFail();

      return $places;
   }
}
