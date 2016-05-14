<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Swipe;

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

   		return response()->json(['ok' => true]);
   }
}
