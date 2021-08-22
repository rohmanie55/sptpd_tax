<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('room',[
            'rooms' => Room::get(),
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'no_ruangan' => 'required|max:100|unique:rooms',
            'nama' => 'required|max:255',
            'tipe' => 'required|max:50',
            'harga' => 'required',
        ]);

        Room::create($request->except('_token'));

        return redirect()->route('room.index')->with('message', 'Success creating room!');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'no_ruangan' => 'required|max:100|unique:rooms,no_ruangan,'.$id,
            'nama' => 'required|max:255',
            'tipe' => 'required|max:50',
            'harga' => 'required',
        ]);

        Room::find($id)->update($request->except('_token'));

        return redirect()->route('room.index')->with('message', 'Success creating room!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            Room::findOrFail($id)->delete();

            return redirect()->route('room.index')->with('success', 'Successfull deleting room!');
       } catch (\Throwable $th) {
            return redirect()->route('room.index')->with('fail', 'Failed deleting room!');
       }
    }
}
