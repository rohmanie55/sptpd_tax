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
                        <a class="nav-link" href="{{ route('trx_f&b.index') }}">
                            <i class="fas fa-fw fa-coffee"></i>
                            <span>Transaksi F&B</span>
                        </a>
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
                            <th>Tagihan</th>
                            <th>Tamu</th>
                            <th>Kamar</th>
                            <th>Asal Perusahaan</th>
                            <th>Option</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($transactions as $key=>$trx_room)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ $trx_room->arrival_at }}</td>
                            <td>{{ $trx_room->departure_at }}</td>
                            <td>@currency($trx_room->total) <br>{{ $trx_room->diskon?  "Diskon $trx_room->diskon %" : ""}}</td>
                            <td>
                                <ul>
                                    @foreach ( $trx_room->guests as $guest)
                                        <li>{{ $guest->guest->nama }} ({{ $guest->guest->tipeID }}: {{ $guest->guest->nomorID }})</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>{{ $trx_room->room->no_ruangan }} - {{ $trx_room->room->nama }} ({{ $trx_room->room->tipe }})</td>
                            <td>{{ $trx_room->company->nama }}</td>
                            <td>
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
            <form action="" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group @error('arrival_at') has-error has-feedback @enderror">
                    <label>Tgl Datang</label>
                    <input name="arrival_at" value="{{ old('arrival_at') }}" type="datetime-local" class="form-control " placeholder="Tgl Datang">
                    @error('arrival_at') 
                    <small class="form-text text-danger">
                        <strong>{{ $message }}</strong>
                    </small> 
                    @enderror
                </div>
                <div class="form-group @error('departure_at') has-error has-feedback @enderror">
                    <label>Tgl Pulang</label>
                    <input name="departure_at" value="{{ old('departure_at') }}" type="datetime-local" class="form-control " placeholder="Tgl Pulang">
                    @error('departure_at') 
                    <small class="form-text text-danger">
                        <strong>{{ $message }}</strong>
                    </small> 
                    @enderror
                </div>

                <div class="form-group @error('diskon') has-error has-feedback @enderror">
                    <label>Diskon</label>
                    <input name="diskon" value="{{ old('diskon') }}" type="number" class="form-control " placeholder="Diskon %">
                    @error('diskon') 
                    <small class="form-text text-danger">
                        <strong>{{ $message }}</strong>
                    </small> 
                    @enderror
                </div>

                <div class="form-group @error('room_id') has-error has-feedback @enderror">
                    <label>Kamar</label>
                    <select name="room_id" class="form-control select2">
                        @foreach ($rooms as $room)
                        <option {{ old('room_id')==$room->id ? 'selected' : ''}} value="{{ $room->id }}">{{ $room->no_ruangan }} - {{ $room->nama }} ({{ $room->tipe }})</option>
                        @endforeach
                    </select>
                    @error('room_id') 
                    <small class="form-text text-danger">
                        <strong>{{ $message }}</strong>
                    </small> 
                    @enderror
                </div>

                <div class="form-group @error('company_id') has-error has-feedback @enderror">
                    <div>
                        <label>Asal Perusahaan</label>
                    </div>
                    <select name="company_id" class="form-control select2">
                        @foreach ($companies as $company)
                        <option {{ old('company_id')==$company->id ? 'selected' : ''}} value="{{ $company->id }}">{{ $company->nama }}</option>
                        @endforeach
                    </select>
                    @error('company_id') 
                    <small class="form-text text-danger">
                        <strong>{{ $message }}</strong>
                    </small> 
                    @enderror
                </div>

                <div class="form-group @error('guest') has-error has-feedback @enderror">
                    <div>
                        <label>Tamu</label>
                    </div>
                    <select name="guest[]" class="form-control select2" multiple="multiple">
                        @foreach ($guests as $guest)
                        <option {{ old('guest')==$guest->id ? 'selected' : ''}} value="{{ $guest->id }}">{{ $guest->nama }} ({{ $guest->tipeID }}: {{ $guest->nomorID }})</option>
                        @endforeach
                    </select>
                    @error('guest') 
                    <small class="form-text text-danger">
                        <strong>{{ $message }}</strong>
                    </small> 
                    @enderror
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
              <button type="submit" class="btn btn-primary">Simpan</button>
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
            modal.find('.modal-body').append(`<input type="hidden" name="_method" value="PUT" id="method">`)

            modal.find('input[name="arrival_at"]').val(new Date(trx_room.arrival_at).toJSON().slice(0,19))
            modal.find('input[name="departure_at"]').val(new Date(trx_room.departure_at).toJSON().slice(0,19))
            modal.find('input[name="diskon"]').val(trx_room.diskon)
            modal.find('select[name="room_id"]').val(trx_room.room_id)
            modal.find('select[name="company_id"]').val(trx_room.company_id)
            modal.find('select[name="guest[]"]').val(trx_guest).change()
        }else{
            $("#method").remove()
        }
    })

    $(document).ready(function() {
        $('#table').DataTable({
        });

        $('.select2').select2({
            dropdownParent: $("#form .modal-content")
        });
    });
</script>
@endsection