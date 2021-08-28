<!DOCTYPE html>
<html>
<head>
	<title>Laporan Revenue</title>
</head>
<style type="text/css">
    body{
      margin-top: 3cm;
      margin-left: 2cm;
      margin-right: 2cm;
      margin-bottom: 2cm;
      font-family: Arial, Helvetica, sans-serif;
      font-size:15px;
    }
    header {
      position: fixed;
      margin-left: 2cm;
      margin-right: 2cm;
      top: 0cm;
      height: 3cm;
    }
    table tr td,
		table tr th{
			padding-top: 5px;
	}

    .collapse{
        border: 1px solid black;
        border-collapse: collapse;
    }
    p{
        text-align:justify;
    }
</style>
<body>
    <header>
        <table>
          <tr>
              <td style="width: 30%">
                  <img src="{{ asset('img/hs.png') }}" style="max-height: 90px">
              </td>
              <td style="width: 70%;padding-left:10px;">
                  <h3>Hotel Santika Cikarang </h3>
                  <span class="small">Jl. Raya Cikarang Cibarusah, Pasirsari, Cikarang Sel</span>
                  <span class="small">Telepon: (021) 89835533 Fax: (021) 89835533 Email: cikarang@reservation.santika.com</span>
              </td>
          </tr>
        </table>
        <hr>
      </header>
      <main>
        <div style="text-align: center;padding-top: 10px;"><h3>Laporan Revenue {{ $year }}</h3></div>
        <table style="width: 100%" class="collapse">
            <tr class="collapse">
                <td class="collapse">No</td>
                <td class="collapse">Periode</td>
                <td class="collapse">Tgl Dari</td>
                <td class="collapse">Tgl Sampai</td>
                <td class="collapse">Transaksi</td>
                <td class="collapse">Room Revenue</td>
                <td class="collapse">Total Diskon</td>
                <td class="collapse">Total Revenue</td>
            </tr>
            @foreach ($transactions as $key=>$trx)
                <tr class="collapse">
                    <td class="collapse">{{ $key+1 }}</td>
                    <td class="collapse">{{ $trx->periode }}</td>
                    <td class="collapse">{{ $trx->min_date }}</td>
                    <td class="collapse">{{ $trx->max_date }}</td>
                    <td class="collapse">{{ $trx->trx_count }}</td>
                    <td class="collapse">@currency($trx->room_total)</td>
                    <td class="collapse">@currency($trx->diskon)</td>
                    <td class="collapse">@currency($trx->fab_total+$trx->room_total-$trx->diskon)</td>
                </tr>
            @endforeach
        </table>
      </main>
</body>