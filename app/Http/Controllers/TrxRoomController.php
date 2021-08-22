<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Room;
use App\Models\Companie;
use App\Models\Guest;
use App\Models\TrxGuest;
use App\Models\FoodBaverage;
use Carbon\Carbon;
use DB;

class TrxRoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('transaction',[
            'transactions' => Transaction::with('guests.guest', 'company:id,nama', 'room:id,no_ruangan,nama,tipe', 'fabs.fab:id,nama,tipe,harga')->get(),
            'rooms' => Room::select('id', 'nama', 'tipe', 'no_ruangan')->get(),
            'companies'=> Companie::select('id', 'nama')->get(),
            'guests'=> Guest::select('id', 'nama', 'nomorID', 'tipeID')->get(),
            'foods'=> FoodBaverage::get(),
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
            'arrival_at' => 'required',
            'departure_at' => 'required|after:arrival_at',
            'room_id' => 'required',
            'company_id' => 'required',
            'guest' => 'required',
        ]);

        try {
            DB::transaction(function () use($request) {
                $hari = Carbon::parse($request->departure_at)->diff(Carbon::parse($request->arrival_at))->days;
                $room= Room::find($request->room_id);

                //hitung total dan subtotal
                $request['jml_hari'] = $hari;
                $request['subtotal'] = $room->harga*$hari;
                
                $trx = Transaction::create($request->except('_token', 'guest'));
                $trxs   = [];

                foreach($request->guest as $guest_id){
                    $trxs[] = ['trx_id'=>$trx->id, 'guest_id'=>$guest_id];
                }

                TrxGuest::insert($trxs);
            });

            return redirect()->route('trx_room.index')->with('message', 'Success creating transaksi!');
        } catch (\Throwable $th) {
            return redirect()->route('trx_room.index')->with('fail', 'Failed creating transaksi!');
        }
        
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
            'arrival_at' => 'required',
            'departure_at' => 'required|after:arrival_at',
            'room_id' => 'required',
            'company_id' => 'required',
            'guest' => 'required',
        ]);

        try {
            DB::transaction(function () use($request, $id) {
                $hari = Carbon::parse($request->departure_at)->diff(Carbon::parse($request->arrival_at))->days;
                $room = Room::find($request->room_id);

                //hitung total dan subtotal
                $request['jml_hari'] = $hari;
                $request['subtotal'] = $room->harga*$hari;

                TrxGuest::where('trx_id', $id)->delete();

                Transaction::find($id)->update($request->except('_token', 'guest'));
                $trxs   = [];

                foreach($request->guest as $guest_id){
                    $trxs[] = ['trx_id'=>$id, 'guest_id'=>$guest_id];
                }

                TrxGuest::insert($trxs);
            });

            return redirect()->route('trx_room.index')->with('message', 'Success update transaksi!');
        } catch (\Throwable $th) {
            return redirect()->route('trx_room.index')->with('fail', 'Failed update transaksi!');
        }
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
            Transaction::findOrFail($id)->delete();

            TrxGuest::where('trx_id', $id)->delete();

            return redirect()->route('trx_room.index')->with('success', 'Successfull deleting transaksi!');
       } catch (\Throwable $th) {
            return redirect()->route('trx_room.index')->with('fail', 'Failed deleting transaksi!');
       }
    }
}
