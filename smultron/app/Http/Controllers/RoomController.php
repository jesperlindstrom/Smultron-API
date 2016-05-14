<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Room;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

       return Room::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!$request->destination) {
            return response()->json(['ok' => false]);
        }

        //TODO: Kolla så att den är unik...

        $code = str_random(6);

        $room = new Room;
        $room->destination = $request->destination;
        $room->code = $code;
        $room->save();

        return response()->json(['ok' => true, 'code' => $code]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($code)
    {
        $room = Room::where('code', $code)->firstOrFail();
        return $room;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $code)
    {
        if(!$request->destination) {
            return response()->json(['ok' => false]);
        }

        $room = Room::where('code', $code)->firstOrFail();
        $room->destination = $request->destination;
        $room->save();

        return response()->json(['ok' => true]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($code)
    {
        $deleted = Room::where('code', $code)->delete();

        return response()->json(['ok' => $deleted]);
    }
}
