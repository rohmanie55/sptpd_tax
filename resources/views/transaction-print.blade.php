<!DOCTYPE html>
<html>
<head>
	<title>Laporan Transaksi</title>
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
        <div style="text-align: center;padding-top: 10px;"><h3>Laporan Transaksi {{ date('F', mktime(0, 0, 0, $param[1], 10))  }} {{ $param[0] }}</h3></div>
        <table style="width: 100%" class="collapse">
            <tr class="collapse">
                <td class="collapse">No</td>
                <td class="collapse">Tgl Datang</td>
                <td class="collapse">Tgl Pulang</td>
                <td class="collapse">Subtotal</td>
                <td class="collapse">Diskon</td>
                <td class="collapse">Tagihan</td>
                <td class="collapse">Tamu</td>
                <td class="collapse">Kamar</td>
                <td class="collapse">Perusahaan</td>
            </tr>
            @foreach ($transactions as $key=>$trx_room)
                <tr class="collapse">
                    <td class="collapse">{{ $key+1 }}</td>
                    <td class="collapse">{{ $trx_room->arrival_at }}</td>
                    <td class="collapse">{{ $trx_room->departure_at }}</td>
                    <td class="collapse">@currency($trx_room->subtotal)</td>
                    <td class="collapse">@currency($trx_room->subdiskon) ({{ $trx_room->diskon }} %)</td>
                    <td class="collapse">
                        @php
                            $subtotal = $trx_room->fabs->sum('total')+$trx_room->subtotal;
                        @endphp
                        @currency($subtotal-$subtotal*$trx_room->diskon/100) <br>{{ $trx_room->diskon?  "Diskon $trx_room->diskon %" : ""}}</td>
                    <td class="collapse">
                        <ul >
                            @foreach ( $trx_room->guests as $guest)
                                <li>{{ $guest->guest->nama }} ({{ $guest->guest->tipeID }}: {{ $guest->guest->nomorID }})</li>
                            @endforeach
                        </ul >
                    </td>
                    <td class="collapse">{{ $trx_room->room->no_ruangan }} - {{ $trx_room->room->nama }} ({{ $trx_room->room->tipe }})</td>
                    <td class="collapse">{{ $trx_room->company->nama }}</td>
                </tr>
            @endforeach
        </table>
      </main>
</body>