@extends('layouts.app')

@section('title')
Master User
@endsection

@section('content')

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <button class="btn btn-sm btn-primary float-right" data-toggle="modal" data-target="#form" data-url="{{ route('user.store') }}" data-title="Tambah User"> <i class="fas fa-plus">Tambah</i></button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="table" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Tgl Dibuat</th>
                            <th>Login terakhir</th>
                            <th>Option</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($users as $key=>$user)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->role }}</td>
                            <td>{{ $user->created_at }}</td>
                            <td>{{ $user->last_login ? \Carbon\Carbon::parse($user->last_login)->diffForHumans() : 'no login' }}</td>
                            <td>
                                <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#form" data-url="{{ route('user.update', $user->id) }}" data-title="Edit User" data-user="{{ json_encode($user) }}"> <i class="fas fa-edit"></i></button>
                                <form 
                                action="{{ route('user.destroy', ['user'=>$user->id]) }}" 
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
            <form action="{{ old('_method') ? route('user.update', old('_id')) : route('user.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label>Nama</label>
                    <input name="name" value="{{ old('name') }}" type="text" class="form-control   @error('name') is-invalid @enderror" placeholder="Nama">
                    @error('name') 
                    <small class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </small> 
                    @enderror
                </div>
                <div class="form-group ">
                    <label>Username</label>
                    <input name="username" value="{{ old('username') }}" type="text" class="form-control @error('username') is-invalid @enderror" placeholder="Username">
                    @error('username') 
                    <small class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </small> 
                    @enderror
                </div>
                <div class="form-group ">
                    <label>Password</label>
                    <input name="password" value="{{ old('password') }}" type="text" class="form-control @error('password') is-invalid @enderror" placeholder="Password">
                    @error('password') 
                    <small class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </small> 
                    @enderror
                </div>
                <div class="form-group ">
                    <label>Role</label>
                    <select name="role" class="form-control @error('role') is-invalid @enderror">
                        <option {{ old('role')=='night_au' ? 'selected' : ''}} value="night_au">Night Audit</option>
                        <option {{ old('role')=='income_au' ? 'selected' : ''}} value="income_au">Income Audit</option>
                        <option {{ old('role')=='payable' ? 'selected' : ''}} value="payable">Account Payable</option>
                        <option {{ old('role')=='manager' ? 'selected' : ''}} value="manager">Manager</option>
                    </select>
                    @error('role') 
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
        let user   = button.data('user') ? button.data('user') : null
        let modal  = $(this)
        modal.find('.modal-title').text(title)
        modal.find('form').attr('action', url)

        if(button.attr('class')=='btn btn-sm btn-info'){
            modal.find('.modal-body').append(`<input type="hidden" name="_method" value="PUT"><input type="hidden" name="_id" value="${user.id}">`)
            modal.find('input[name="name"]').val(user.name)
            modal.find('input[name="username"]').val(user.username)
            modal.find('select[name="role"]').val(user.role)
        }else{
            $("#form input[name='_method']").remove()
            $("#form input[name='_id']").remove()
        }
    })

    $(document).ready(function() {
        $('#table').DataTable({
        });
    });
</script>
@endsection