<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TrxSptpd;
use App\Models\Transaction;
use PDF;

class TaxSPTPDController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $year = isset($_GET['year']) ? $_GET['year'] : date('Y');

        $transactions = TrxSptpd::with('insert:id,name', 'approve:id,name')
                        ->where('periode', 'LIKE', "%$year%")
                        ->where('approve_at', '<>', null)
                        ->orderBy('created_at')
                        ->get();

        if(isset($_GET['print'])){
            return PDF::loadView('sptpd-print', ["transactions"=>$transactions, "year"=>$year])
            ->setPaper('a4', 'landscape')
            ->stream("laporan_revenue_$year.pdf");
        }

        return view('sptpd',[
            'year' => $year,
            'transactions' => $transactions,
            'periodes' => Transaction::selectRaw("CONCAT(MONTH(arrival_at),'-',YEAR(arrival_at)) as periode, COALESCE(SUM(subtotal),0)-COALESCE(SUM(subdiskon),0) as total")
            ->groupBy('periode')
            ->get()
        ]);
    }

        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function approve($id)
    {
        TrxSptpd::find($id)->update(['approve_by'=>auth()->user()->id, 'approve_at'=>now()->toDateTimeString()]);

        return redirect()->route('trx_sptpd.index')->with('message', 'Success approving sptpd!');
    }

            /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function status($id)
    {
        TrxSptpd::find($id)->update(['status'=>'paid']);

        return redirect()->route('trx_sptpd.index')->with('message', 'Success update status sptpd!');
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
            'periode'=> 'required|unique:trx_sptpds',
            'no_bill'=> 'required',
            'total'  => 'required',
        ]);

        $request['create_by'] = auth()->user()->id;
        $request['status'] = 'unpaid';

        TrxSptpd::create($request->except('_token'));

        return redirect()->route('trx_sptpd.index')->with('message', 'Success creating sptpd!');
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
            'periode'=> 'required:unique:trx_sptpds,periode,'.$id,
            'no_bill'=> 'required',
            'total'  => 'required',
        ]);

        TrxSptpd::find($id)->update($request->except('_token'));

        return redirect()->route('trx_sptpd.index')->with('message', 'Success creating sptpd!');
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
            TrxSptpd::findOrFail($id)->delete();

            return redirect()->route('trx_room.index')->with('success', 'Successfull deleting sptpd!');
       } catch (\Throwable $th) {
            return redirect()->route('trx_room.index')->with('fail', 'Failed deleting sptpd!');
       }
    }
}
