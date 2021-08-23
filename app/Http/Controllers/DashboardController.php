<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Room;
use App\Models\Guest;
use App\Models\TrxGuest;

class DashboardController extends Controller
{
    public function __invoke(Request $request) {
        $month = isset($_GET['month']) ? $_GET['month'] : date('Y-m');
        $param = explode('-', $month);

        $revenue=Transaction::selectRaw("SUM(subtotal)-COALESCE(SUM(subdiskon),0) as total, CONCAT(MONTH(arrival_at),'-',YEAR(arrival_at)) as periode")->whereMonth('arrival_at', $param[1])->whereYear('arrival_at', $param[0])->groupBy('periode')->first();

        $overview = Transaction::selectRaw("SUM(subtotal)-COALESCE(SUM(subdiskon),0) as total, DATE_FORMAT(arrival_at, '%d') as date_arival")->whereMonth('arrival_at', $param[1])
            ->whereYear('arrival_at', $param[0])->groupBy('date_arival')->get();
        //dd($overview);
        return view('dashboard',[
            'month'=> $month,
            'room' =>Room::whereHas('transactions',function($query) use($param){ 
                $query->whereMonth('arrival_at', $param[1])->whereYear('arrival_at', $param[0]);
            })->count(),
            'guest'=> TrxGuest::whereHas('transaction',function($query) use($param){ 
                $query->whereMonth('arrival_at', $param[1])->whereYear('arrival_at', $param[0]);
            })->count(),
            'trx'=> Transaction::whereMonth('arrival_at', $param[1])->whereYear('arrival_at', $param[0])->count(),
            'revenue'=>$revenue ,
            'overview'=>$overview,
        ]);
    }
}
