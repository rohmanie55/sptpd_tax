@extends('layouts.app')

@section('title')
Transaksi 
@endsection

@section('content')
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex">
            <div class="col-10">
                <ul class="nav nav-pills">
                    <li class="nav-item">
                      <a class="nav-link active" href="{{ route('trx_room.index') }}">
                        <i class="fas fa-fw fa-book"></i>
                        Transaksi</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="{{ route('company.index') }}">
                        <i class="fas fa-fw fa-list"></i>
                        Daftar Perusahaan</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="{{ route('guest.index') }}">
                        <i class="fas fa-fw fa-address-book"></i>
                        Daftar Tamu</a>
                    </li>
                  </ul>
            </div>
            <div class="col-2">
                <button class="btn btn-sm btn-primary float-right" data-toggle="modal" data-target="#form" data-url="{{ route('trx_room.store') }}" data-title="Tambah Transaksi"> <i class="fas fa-plus">Tambah</i></button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="table" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tgl Datang</th>
                            <th>Tgl Pulang</th>
                            <th>Subtotal</th>
                            <th>Tagihan</th>
                            <th>Tamu</th>
                            <th>Kamar</th>
                            <th>Asal Perusahaan</th>
                            {{-- <th class="none">Detail F&B</th> --}}
                            <th style="width: 13%">Option</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($transactions as $key=>$trx_room)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ $trx_room->arrival_at }}</td>
                            <td>{{ $trx_room->departure_at }}</td>
                            <td>@currency($trx_room->subtotal)</td>
                            <td>
                                @php
                                    $subtotal = $trx_room->fabs->sum('total')+$trx_room->subtotal;
                                @endphp
                                @currency($subtotal-$subtotal*$trx_room->diskon/100) <br>{{ $trx_room->diskon?  "Diskon $trx_room->diskon %" : ""}}</td>
                            <td>
                                <ul>
                                    @foreach ( $trx_room->guests as $guest)
                                        <li>{{ $guest->guest->nama }} ({{ $guest->guest->tipeID }}: {{ $guest->guest->nomorID }})</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>{{ $trx_room->room->no_ruangan }} - {{ $trx_room->room->nama }} ({{ $trx_room->room->tipe }})</td>
                            <td>{{ $trx_room->company->nama }}</td>
                            {{-- <td>
                                <div>
                                <table class="table table-bordered" style="width: 100%">
                                    <tr>
                                        <th>#</th>
                                        <th style="width: 40%">Food & Baverage</th>
                                        <th style="width: 15%">Qty</th>
                                        <th style="width: 20%">Total</th>
                                        <th style="width: 20%">Option</th>
                                    </tr>
                                    @foreach ($trx_room->fabs as $key=>$fab)
                                        <tr>
                                            <td>{{ $key+1 }}</td>
                                            <td>{{ $fab->fab->tipe}} | {{ $fab->fab->nama}} (@currency($fab->fab->harga))</td>
                                            <td>{{ $fab->qty }}</td>
                                            <td>@currency($fab->total)</td>
                                            <td>
                                                <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#form-fab" data-url="{{ route('trx_fab.update', $fab->id) }}" data-title="Edit Transaksi F&B" data-fab="{{ json_encode($fab) }}"> <i class="fas fa-edit"></i></button>
                                                <form 
                                                action="{{ route('trx_fab.destroy', ['trx_fab'=>$fab->id]) }}" 
                                                method="POST"
                                                style="display: inline"
                                                onsubmit="return confirm('Are you sure to delete this data?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-danger"> <i class="fas fa-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                                </div>
                            </td> --}}
                            <td>
                                {{-- <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#form-fab" data-url="{{ route('trx_fab.store', ['trx_id'=>$trx_room->id]) }}" data-title="Tambah Transaksi F&B"> <i class="fas fa-plus"></i></button> --}}

                                <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#form" data-url="{{ route('trx_room.update', $trx_room->id) }}" data-title="Edit Transaksi" data-trx_room="{{ json_encode($trx_room) }}" data-trx_guest="{{ json_encode($trx_room->guests->pluck('guest_id')) }}"> <i class="fas fa-edit"></i></button>
                                <form 
                                action="{{ route('trx_room.destroy', ['trx_room'=>$trx_room->id]) }}" 
                                method="POST"
                                style="display: inline"
                                onsubmit="return confirm('Are you sure to delete this data?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger"> <i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="form"  aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Modal title</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="{{ old('_method') ? route('trx_room.update', old('_id')) : route('trx_room.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group ">
                    <label>Tgl Datang</label>
                    <input name="arrival_at" value="{{ old('arrival_at') }}" type="datetime-local" class="form-control @error('arrival_at') is-invalid @enderror" placeholder="Tgl Datang">
                    @error('arrival_at') 
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </small> 
                    @enderror
                </div>
                <div class="form-group ">
                    <label>Tgl Pulang</label>
                    <input name="departure_at" value="{{ old('departure_at') }}" type="datetime-local" class="form-control @error('departure_at') is-invalid @enderror" placeholder="Tgl Pulang">
                    @error('departure_at') 
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </small> 
                    @enderror
                </div>

                <div class="form-group ">
                    <label>Diskon</label>
                    <input name="diskon" value="{{ old('diskon') }}" type="number" class="form-control @error('diskon') is-invalid @enderror" placeholder="Diskon %">
                    @error('diskon') 
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </small> 
                    @enderror
                </div>

                <div class="form-group ">
                    <label>Room</label>
                    <select name="room_id" class="form-control select2 @error('room_id') is-invalid @enderror">
                        @foreach ($rooms as $room)
                        <option {{ old('room_id')==$room->id ? 'selected' : ''}} value="{{ $room->id }}">{{ $room->no_ruangan }} - {{ $room->nama }} ({{ $room->tipe }})</option>
                        @endforeach
                    </select>
                    @error('room_id') 
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </small> 
                    @enderror
                </div>

                <div class="form-group ">
                    <div>
                        <label>Asal Perusahaan</label>
                    </div>
                    <select name="company_id" class="form-control select2 @error('company_id') is-invalid @enderror">
                        @foreach ($companies as $company)
                        <option {{ old('company_id')==$company->id ? 'selected' : ''}} value="{{ $company->id }}">{{ $company->nama }}</option>
                        @endforeach
                    </select>
                    @error('company_id') 
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </small> 
                    @enderror
                </div>

                <div class="form-group @error('guest') is-invalid @enderror">
                    <div>
                        <label>Tamu</label>
                    </div>
                    <select name="guest[]" class="form-control select2" multiple="multiple">
                        @foreach ($guests as $guest)
                        <option {{ old('guest') && in_array($guest->id, old('guest')) ? 'selected' : ''}} value="{{ $guest->id }}">{{ $guest->nama }} ({{ $guest->tipeID }}: {{ $guest->nomorID }})</option>
                        @endforeach
                    </select>
                    @error('guest') 
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </small> 
                    @enderror
                </div>

                @if (old('_id'))
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="_id" value="{{ old('_id') }}">
                @endif
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
              <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
            </form>
          </div>
        </div>
    </div>

    <div class="modal fade" id="form-fab" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">{{ old('_id') ? "Edit" : "Tambah" }} Transaksi</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group ">
                    <label>Tgl Transaksi</label>
                    <input name="tgl_trx" value="{{ old('tgl_trx') }}" type="datetime-local" class="form-control @error('tgl_trx') is-invalid @enderror" placeholder="Tgl Transaksi">
                    @error('tgl_trx') 
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </small> 
                    @enderror
                </div>

                <div class="form-group ">
                    <label>Pilih F&B</label>
                    <select name="fab_id" class="form-control select2 @error('fab_id') is-invalid @enderror">
                        @foreach ($foods as $fab)
                        <option {{ old('fab_id')==$fab->id ? 'selected' : ''}} value="{{ $fab->id }}">{{ $fab->tipe }} | {{ $fab->nama }}  (@currency($fab->harga))</option>
                        @endforeach
                    </select>
                    @error('fab_id') 
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </small> 
                    @enderror
                </div>

                <div class="form-group ">
                    <label>Qty</label>
                    <input name="qty" value="{{ old('qty') }}" type="number" class="form-control form-control-user @error('qty') is-invalid @enderror" placeholder="Qty">
                    @error('qty') 
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </small> 
                    @enderror
                </div>

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
              <button type="submit" class="btn btn-primary">{{ old('_id') ? "Update" : "Simpan" }}</button>
            </div>
            </form>
          </div>
        </div>
    </div>
@endsection

@section('script')
<script >
    @if (count($errors->all())>0)
    $('#form').modal('show')   
    @endif

    $('#form').on('show.bs.modal', function (event) {
        let button = $(event.relatedTarget)
        let title  = button.data('title') 
        let url    = button.data('url')
        let trx_room   = button.data('trx_room') ? button.data('trx_room') : null
        let trx_guest  = button.data('trx_guest') ? button.data('trx_guest') : null

        let modal  = $(this)
        modal.find('.modal-title').text(title)
        modal.find('form').attr('action', url)

        if(button.attr('class')=='btn btn-sm btn-info'){
            modal.find('.modal-body').append(`<input type="hidden" name="_method" value="PUT"><input type="hidden" name="_id" value="${trx_room.id}">`)
            let arrival    = new Date(trx_room.arrival_at)
            let departure  = new Date(trx_room.departure_at)
            arrival.setMinutes(arrival.getMinutes() - arrival.getTimezoneOffset());
            departure.setMinutes(departure.getMinutes() - departure.getTimezoneOffset());
            
            modal.find('input[name="arrival_at"]').val(arrival.toISOString().slice(0,16))
            modal.find('input[name="departure_at"]').val(departure.toISOString().slice(0,16))
            modal.find('input[name="diskon"]').val(trx_room.diskon)
            modal.find('select[name="room_id"]').val(trx_room.room_id)
            modal.find('select[name="company_id"]').val(trx_room.company_id)
            modal.find('select[name="guest[]"]').val(trx_guest).change()
            modal.find('.btn-primary').text('Update')
        }else{
            $("#form input[name='_method']").remove()
            $("#form input[name='_id']").remove()

            modal.find('input[name="arrival_at"]').val("")
            modal.find('input[name="departure_at"]').val("")
            modal.find('input[name="diskon"]').val("")
            modal.find('select[name="room_id"]').val("")
            modal.find('select[name="company_id"]').val("")
            modal.find('select[name="guest[]"]').val([]).change()
            modal.find('.btn-primary').text('Simpan')
        }
    })

    $('#form-fab').on('show.bs.modal', function (event) {
        let button = $(event.relatedTarget)
        let title  = button.data('title') 
        let url    = button.data('url')
        let trx_fab= button.data('fab') ? button.data('fab') : null
        let modal  = $(this)
        modal.find('.modal-title').text(title)
        modal.find('form').attr('action', url)

        if(button.attr('class')=='btn btn-sm btn-info'){
            modal.find('.modal-body').append(`<input type="hidden" name="_method" value="PUT" id="method">`)

            modal.find('input[name="tgl_trx"]').val(new Date(trx_fab.tgl_trx).toJSON().slice(0,19))
            modal.find('input[name="qty"]').val(trx_fab.qty)
            modal.find('select[name="fab_id"]').val(trx_fab.fab_id)
        }else{
            $("#form-fab input[name='_method']").remove()
        }
    })

    $(document).ready(function() {
        $('#table').DataTable({
            'responsive': true
        });

        $('.select2').select2({
            dropdownParent: $("#form .modal-content")
        });
    });
</script>
@endsection