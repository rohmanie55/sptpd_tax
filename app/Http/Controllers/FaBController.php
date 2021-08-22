<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FoodBaverage;

class FaBController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('food',[
            'fabs' => FoodBaverage::get(),
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
            'tipe' => 'required|max:50',
            'harga' => 'required',
        ]);

        FoodBaverage::create($request->except('_token'));

        return redirect()->route('fab.index')->with('message', 'Success creating f&b!');
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
            'tipe' => 'required|max:50',
            'harga' => 'required',
        ]);

        FoodBaverage::find($id)->update($request->except('_token'));

        return redirect()->route('fab.index')->with('message', 'Success creating f&b!');
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
            FoodBaverage::findOrFail($id)->delete();

            return redirect()->route('fab.index')->with('success', 'Successfull deleting f&b!');
       } catch (\Throwable $th) {
            return redirect()->route('fab.index')->with('fail', 'Failed deleting f&b!');
       }
    }
}
