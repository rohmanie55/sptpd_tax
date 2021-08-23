@extends('layouts.app')

@section('title')
Daftar Perusahaan
@endsection

@section('content')

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex">
            <div class="col-10">
                <ul class="nav nav-pills">
                    <li class="nav-item">
                        <a class="nav-link " href="{{ route('trx_room.index') }}">
                          <i class="fas fa-fw fa-book"></i>
                          Transaksi</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link " href="{{ route('company.index') }}">
                          <i class="fas fa-fw fa-list"></i>
                          Daftar Perusahaan</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link active" href="{{ route('guest.index') }}">
                          <i class="fas fa-fw fa-address-book"></i>
                          Daftar Tamu</a>
                      </li>
                  </ul>
            </div>
            <div class="col-2">
                <button class="btn btn-sm btn-primary float-right" data-toggle="modal" data-target="#form" data-url="{{ route('guest.store') }}" data-title="Tambah Tamu"> <i class="fas fa-plus">Tambah</i></button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="table" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th style="width: 5%">#</th>
                            <th>Nama</th>
                            <th>Jenis Identitas</th>
                            <th>Nomor Identitas</th>
                            <th style="width: 10%">Option</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($guests as $key=>$guest)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ $guest->nama }}</td>
                            <td>{{ $guest->tipeID }}</td>
                            <td>{{ $guest->nomorID }}</td>
                            <td>
                                <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#form" data-url="{{ route('guest.update', $guest->id) }}" data-title="Edit Tamu" data-guest="{{ json_encode($guest) }}"> <i class="fas fa-edit"></i></button>
                                <form 
                                action="{{ route('guest.destroy', ['guest'=>$guest->id]) }}" 
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
            <form action="{{ old('_method') ? route('guest.update', old('_id')) : route('guest.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group ">
                    <label>Nama</label>
                    <input name="nama" value="{{ old('nama') }}" type="text" class="form-control  @error('nama') is-invalid @enderror" placeholder="Nama">
                    @error('nama') 
                    <small class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </small> 
                    @enderror
                </div>
                <div class="form-group ">
                    <label>Jenis Identitas</label>
                    <input name="tipeID" value="{{ old('tipeID') }}" type="text" class="form-control @error('tipeID') is-invalid @enderror" placeholder="Jenis Identitas">
                    @error('tipeID') 
                    <small class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </small> 
                    @enderror
                </div>
                <div class="form-group ">
                    <label>No Identitas</label>
                    <input name="nomorID" value="{{ old('nomorID') }}" type="text" class="form-control @error('nomorID') is-invalid @enderror" placeholder="No Identitas">
                    @error('nomorID') 
                    <small class="invalid-feedback">
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
        let guest   = button.data('guest') ? button.data('guest') : null
        let modal  = $(this)
        modal.find('.modal-title').text(title)
        modal.find('form').attr('action', url)

        if(button.attr('class')=='btn btn-sm btn-info'){
            modal.find('.modal-body').append(`<input type="hidden" name="_method" value="PUT"><input type="hidden" name="_id" value="${guest.id}">`)
            modal.find('input[name="nama"]').val(guest.nama)
            modal.find('input[name="tipeID"]').val(guest.tipeID)
            modal.find('input[name="nomorID"]').val(guest.nomorID)
        }else{
            $("#form input[name='_method']").remove()
            $("#form input[name='_id']").remove()
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