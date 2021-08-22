<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Guest;

class GuestController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('guest',[
            'guests' => Guest::get(),
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
            'nama' => 'required|max:191',
            'tipeID' => 'required|max:20',
            'nomorID' => 'required|max:50',
        ]);

        Guest::create($request->except('_token'));

        return redirect()->route('guest.index')->with('message', 'Success creating guest!');
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
            'nama' => 'required|max:191',
            'tipeID' => 'required|max:20',
            'nomorID' => 'required|max:50',
        ]);

        Guest::find($id)->update($request->except('_token'));

        return redirect()->route('guest.index')->with('message', 'Success creating guest!');
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
            Guest::findOrFail($id)->delete();

            return redirect()->route('guest.index')->with('success', 'Successfull deleting guest!');
       } catch (\Throwable $th) {
            return redirect()->route('guest.index')->with('fail', 'Failed deleting guest!');
       }
    }
}
