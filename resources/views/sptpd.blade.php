@extends('layouts.app')

@section('title')
Pajak SPTPD
@endsection

@section('content')

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <button class="btn btn-sm btn-primary float-right mt-1 ml-1" data-toggle="modal" data-target="#form" data-url="{{ route('trx_sptpd.store') }}" data-title="Tambah SPTPD"> <i class="fas fa-plus">Tambah</i></button>
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
                            <th>No Billing</th>
                            <th>Total Pembayaran</th>
                            <th>Status</th>
                            <th>Deskripsi</th>
                            <th>Input By</th>
                            <th>Approve</th>
                            <th>Option</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($transactions as $key=>$trx_sptpd)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ $trx_sptpd->periode }}</td>
                            <td>{{ $trx_sptpd->no_bill }}</td>
                            <td>@currency($trx_sptpd->total)</td>
                            <td>{{ $trx_sptpd->status }}</td>
                            <td>{{ $trx_sptpd->deskripsi }}</td>
                            <td>{{ $trx_sptpd->insert->name }}</td>
                            <td>{{ $trx_sptpd->approve->name ?? "" }}</td>
                            <td>
                                @if (!$trx_sptpd->approve_at && auth()->user()->role=='manager')
                                <form 
                                action="{{ route('trx_sptpd.approve', $trx_sptpd->id) }}" 
                                method="POST"
                                style="display: inline"
                                onsubmit="return confirm('Are you sure to approve this data?')">
                                    @csrf
                                    <button class="btn btn-sm btn-success"> <i class="fas fa-thumbs-up"></i></button>
                                </form>
                                @endif

                                @if ($trx_sptpd->status=='unpaid' && $trx_sptpd->approve_at)
                                <form 
                                action="{{ route('trx_sptpd.status', $trx_sptpd->id) }}" 
                                method="POST"
                                style="display: inline"
                                onsubmit="return confirm('Are you sure set status pay?')">
                                    @csrf
                                    <button class="btn btn-sm btn-warning"> <i class="fas fa-check"></i></button>
                                </form>
                                @endif

                                <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#form" data-url="{{ route('trx_sptpd.update', $trx_sptpd->id) }}" data-title="Edit SPTPD" data-trx_sptpd="{{ json_encode($trx_sptpd) }}"> <i class="fas fa-edit"></i></button>
                                <form 
                                action="{{ route('trx_sptpd.destroy', ['trx_sptpd'=>$trx_sptpd->id]) }}" 
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
              <h5 class="modal-title">{{ old('_id') ? "Edit" : "Tambah" }} SPTPD</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="{{ old('_method') ? route('trx_sptpd.update', old('_id')) : route('trx_sptpd.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group ">
                    <label>Periode</label>
                    <select name="periode" class="form-control @error('periode') is-invalid @enderror">
                        <option value="" readonly>-- Pilih Periode --</option>
                        @foreach ($periodes as $periode)
                        <option value="{{ $periode->periode }}" {{ old('periode')==$periode->periode ? 'selected' : ''}}>{{$periode->periode}} - Revenue: @currency($periode->total)</option>
                        @endforeach
                    </select>
                    @error('periode') 
                    <small class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </small> 
                    @enderror
                </div>
                <div class="form-group">
                    <label>No Billing</label>
                    <input name="no_bill" value="{{ old('no_bill') }}" type="text" class="form-control   @error('no_bill') is-invalid @enderror" placeholder="No Billing">
                    @error('no_bill') 
                    <small class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </small> 
                    @enderror
                </div>
                <div class="form-group">
                    <label>Total Bayar (10% Revenue)</label>
                    <input name="total" value="{{ old('total') }}" type="number" class="form-control   @error('total') is-invalid @enderror" placeholder="Total Bayar">
                    @error('total') 
                    <small class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </small> 
                    @enderror
                </div>
                <div class="form-group ">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror"></textarea>
                    @error('deskripsi') 
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

    let periodes = @json($periodes)

    $("#form select[name='periode']").on('change', function() {
        let periode = periodes.filter(p=>p.periode==$(this).val())
        $("#form input[name='total']").val(parseInt(periode[0].total*0.1))
    });

    $('#form').on('show.bs.modal', function (event) {
        let button = $(event.relatedTarget)
        let title  = button.data('title') 
        let url    = button.data('url')
        let trx_sptpd   = button.data('trx_sptpd') ? button.data('trx_sptpd') : null
        let modal  = $(this)
        modal.find('.modal-title').text(title)
        modal.find('form').attr('action', url)

        if(button.attr('class')=='btn btn-sm btn-info'){
            modal.find('.modal-body').append(`<input type="hidden" name="_method" value="PUT"><input type="hidden" name="_id" value="${trx_sptpd.id}">`)
            modal.find('select[name="periode"]').val(trx_sptpd.periode)
            modal.find('input[name="no_bill"]').val(trx_sptpd.no_bill)
            modal.find('input[name="total"]').val(trx_sptpd.total)
            modal.find('textarea[name="deskripsi"]').val(trx_sptpd.deskripsi)
            modal.find('.btn-primary').text('Update')
        }else{
            $("#form input[name='_method']").remove()
            $("#form input[name='_id']").remove()

            modal.find('select[name="periode"]').val("")
            modal.find('input[name="no_bill"]').val("")
            modal.find('input[name="total"]').val("")
            modal.find('textarea[name="deskripsi"]').val("")
            modal.find('.btn-primary').text('Simpan')
        }
    })

    $(document).ready(function() {
        $('#table').DataTable({
        });
    });
</script>
@endsection
