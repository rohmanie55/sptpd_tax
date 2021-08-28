@extends('layouts.app')

@section('title')
Master Room
@endsection

@section('content')

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <button class="btn btn-sm btn-primary float-right" data-toggle="modal" data-target="#form" data-url="{{ route('room.store') }}" data-title="Tambah Room"> <i class="fas fa-plus">Tambah</i></button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="table" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>No Room</th>
                            <th>Nama</th>
                            <th>Tipe</th>
                            <th>Harga</th>
                            <th>Fasilitas</th>
                            <th>Option</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($rooms as $key=>$room)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ $room->no_ruangan }}</td>
                            <td>{{ $room->nama }}</td>
                            <td>{{ $room->tipe }}</td>
                            <td>@currency($room->harga)</td>
                            <td>{{ $room->fasilitas }}</td>
                            <td>
                                <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#form" data-url="{{ route('room.update', $room->id) }}" data-title="Edit Room" data-room="{{ json_encode($room) }}"> <i class="fas fa-edit"></i></button>
                                <form 
                                action="{{ route('room.destroy', ['room'=>$room->id]) }}" 
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

    <div class="modal fade" id="form" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">{{ old('_id') ? "Edit" : "Tambah" }} Room</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="{{ old('_method') ? route('room.update', old('_id')) : route('room.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group ">
                    <label>No Room</label>
                    <input name="no_ruangan" value="{{ old('no_ruangan') }}" type="number" class="form-control @error('no_ruangan') is-invalid @enderror" placeholder="Nomor Room">
                    @error('no_ruangan') 
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </small> 
                    @enderror
                </div>
                <div class="form-group ">
                    <label>Nama</label>
                    <input name="nama" value="{{ old('nama') }}" type="text" class="form-control  @error('nama') is-invalid @enderror" placeholder="Nama">
                    @error('nama') 
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </small> 
                    @enderror
                </div>
                <div class="form-group ">
                    <label>Tipe</label>
                    <input name="tipe" value="{{ old('tipe') }}" type="text" class="form-control  @error('tipe') is-invalid @enderror" placeholder="Tipe">
                    @error('tipe') 
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </small> 
                    @enderror
                </div>
                <div class="form-group ">
                    <label>Harga</label>
                    <input name="harga" value="{{ old('harga') }}" type="text" class="form-control @error('harga') is-invalid @enderror" placeholder="Harga">
                    @error('harga') 
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </small> 
                    @enderror
                </div>
                <div class="form-group ">
                    <label>Fasilitas</label>
                    <textarea name="fasilitas" class="form-control @error('fasilitas') is-invalid @enderror"></textarea>
                    @error('fasilitas') 
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
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
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
        let room   = button.data('room') ? button.data('room') : null
        let modal  = $(this)
        modal.find('.modal-title').text(title)
        modal.find('form').attr('action', url)

        if(button.attr('class')=='btn btn-sm btn-info'){
            modal.find('.modal-body').append(`<input type="hidden" name="_method" value="PUT"><input type="hidden" name="_id" value="${room.id}">`)
            modal.find('input[name="no_ruangan"]').val(room.no_ruangan)
            modal.find('input[name="nama"]').val(room.nama)
            modal.find('input[name="tipe"]').val(room.tipe)
            modal.find('input[name="harga"]').val(room.harga)
            modal.find('textarea[name="fasilitas"]').val(room.fasilitas)
            modal.find('.btn-primary').text('Update')
        }else{
            $("#form input[name='_method']").remove()
            $("#form input[name='_id']").remove()
            modal.find('input[name="no_ruangan"]').val("")
            modal.find('input[name="nama"]').val("")
            modal.find('input[name="tipe"]').val("")
            modal.find('input[name="harga"]').val("")
            modal.find('textarea[name="fasilitas"]').val("")
            modal.find('.btn-primary').text('Simpan')
        }
    })

    $(document).ready(function() {
        $('#table').DataTable({
        });
    });
</script>
@endsection