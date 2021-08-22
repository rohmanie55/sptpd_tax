<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransactionFb;
use App\Models\FoodBaverage;
use App\Models\Transaction;
use DB;

class TrxFaBController extends Controller
{


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'tgl_trx' => 'required',
            'fab_id' => 'required',
            'qty' => 'required',
        ]);

        $fab = FoodBaverage::find($request->fab_id);

        $request['total'] = $request->qty * $fab->harga;

        TransactionFb::create($request->except('_token'));

        return redirect()->route('trx_room.index')->with('message', 'Success creating transaction f&b!');
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
            'tgl_trx' => 'required',
            'fab_id' => 'required',
            'qty' => 'required',
        ]);

        $fab = FoodBaverage::find($request->fab_id);

        $request['total'] = $request->qty * $fab->harga;

        TransactionFb::find($id)->update($request->except('_token'));

        return redirect()->route('trx_room.index')->with('message', 'Success updating transaction f&b!');
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
            TransactionFb::findOrFail($id)->delete();

            return redirect()->route('trx_room.index')->with('success', 'Successfull deleting transaction f&b!');
       } catch (\Throwable $th) {
            return redirect()->route('trx_room.index')->with('fail', 'Failed deleting transaction f&b!');
       }
    }
}
