@extends('layouts.app')

@section('title')
Revenue
@endsection

@section('content')

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <form action="" class="form-inline float-right" style="display: inline">
                <select name="year" class="form-control" style="width:150px">
                    @for ($i = 2019; $i <= date('Y'); $i++)
                    <option {{ $i==$year ? "selected" : ""}}>{{ $i }}</option>
                    @endfor
                </select>
                <button class="btn btn-sm btn-danger" name="print"> <i class="fas fa-file-pdf"></i></button>
                <button class="btn btn-sm btn-secondary"> <i class="fas fa-search"></i></button>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="table" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Periode</th>
                            <th>Tgl Dari</th>
                            <th>Tgl Sampai</th>
                            <th>Transaksi</th>
                            <th>Room Revenue</th>
                            {{-- <th>Total F&B</th>
                            <th>F&B Revenue</th> --}}
                            <th>Total Diskon</th>
                            <th>Total Revenue</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($transactions as $key=>$trx)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ $trx->periode }}</td>
                            <td>{{ $trx->min_date }}</td>
                            <td>{{ $trx->max_date }}</td>
                            <td>{{ $trx->trx_count }}</td>
                            <td>@currency($trx->room_total)</td>
                            {{-- <td>{{ $trx->fab_count }}</td>
                            <td>@currency($trx->fab_total)</td> --}}
                            <td>@currency($trx->diskon)</td>
                            <td>@currency($trx->fab_total+$trx->room_total-$trx->diskon)</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

@section('script')
<script >
    $(document).ready(function() {
        $('#table').DataTable({
        });
    });
</script>
@endsection