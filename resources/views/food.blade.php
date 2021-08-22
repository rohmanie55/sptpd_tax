@extends('layouts.app')

@section('title')
Master F&B
@endsection

@section('content')

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <button class="btn btn-sm btn-primary float-right" data-toggle="modal" data-target="#form" data-url="{{ route('fab.store') }}" data-title="Tambah F&B"> <i class="fas fa-plus">Tambah</i></button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="table" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Tipe</th>
                            <th>Harga</th>
                            <th>Deskripsi</th>
                            <th>Option</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($fabs as $key=>$fab)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ $fab->nama }}</td>
                            <td>{{ $fab->tipe }}</td>
                            <td>{{ $fab->harga }}</td>
                            <td>{{ $fab->deskripsi }}</td>
                            <td>
                                <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#form" data-url="{{ route('fab.update', $fab->id) }}" data-title="Edit F&B" data-fab="{{ json_encode($fab) }}"> <i class="fas fa-edit"></i></button>
                                <form 
                                action="{{ route('fab.destroy', ['fab'=>$fab->id]) }}" 
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
              <h5 class="modal-title">Modal title</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group ">
                    <label>Nama</label>
                    <input name="nama" value="{{ old('nama') }}" type="text" class="form-control form-control-user @error('nama') is-invalid @enderror" placeholder="Nama">
                    @error('nama') 
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </small> 
                    @enderror
                </div>
                <div class="form-group ">
                    <label>Tipe</label>
                    <select name="tipe" class="form-control form-control-user @error('tipe') is-invalid @enderror">
                        <option value="food">Food</option>
                        <option value="baverage">Beverage</option>
                    </select>
                    @error('tipe') 
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </small> 
                    @enderror
                </div>
                <div class="form-group ">
                    <label>Harga</label>
                    <input name="harga" value="{{ old('harga') }}" type="text" class="form-control form-control-user @error('harga') is-invalid @enderror" placeholder="Harga">
                    @error('harga') 
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </small> 
                    @enderror
                </div>
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" class="form-control form-control-user @error('deskripsi') is-invalid @enderror"></textarea>
                    @error('deskripsi') 
                    <span class="invalid-feedback" role="alert">
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
        let fab   = button.data('fab') ? button.data('fab') : null
        let modal  = $(this)
        modal.find('.modal-title').text(title)
        modal.find('form').attr('action', url)

        if(button.attr('class')=='btn btn-sm btn-info'){
            modal.find('.modal-body').append(`<input type="hidden" name="_method" value="PUT" id="method">`)
            modal.find('input[name="nama"]').val(fab.nama)
            modal.find('select[name="tipe"]').val(fab.tipe)
            modal.find('input[name="harga"]').val(fab.harga)
            modal.find('textarea[name="deskripsi"]').val(fab.deskripsi)
        }else{
            $("input[name='_method']").remove()
        }
    })

    $(document).ready(function() {
        $('#table').DataTable({
        });
    });
</script>
@endsection