@extends('main')
@section('content')
<div class="content-wrapper">
  <div class="row">
    <div class="col-lg-12">
      <nav aria-label="breadcrumb" role="navigation">
        <ol class="breadcrumb bg-info">
          <li class="breadcrumb-item"><i class="fa fa-home"></i>&nbsp;<a href="{{url('admin/transaksi-koperasi')}}">Transaksi Koperasi</a></li>
          <li class="breadcrumb-item active" aria-current="page">Edit Data</li>
        </ol>
      </nav>
    </div>
  	<div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            
           <h4 class="card-title">Edit Transaksi Koperasi</h4>
           
                    <!-- Modal -->
    <!-- Modal content-->

    <div class="row">
      @if ($errors->any())
      <div class="alert alert-danger">
      <ul>
          @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
          @endforeach
      </ul>
      </div>
     @endif
    </div>
        <div class="row">
          
          <form action="{{url('admin/transaksi-koperasi/update')}}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
        
            <tr>
              <td>Total Pembayaran<span style="color:red;">*</span></td>
              <td>
                <input type="hidden" class="form-control form-control-sm id" value="{{$data->id}}" name="id">
                <input type="text" class="form-control form-control-sm inputtext totalPembayaran" value="{{$data->total_pembayaran}}" name="total_pembayaran" disabled>
              </td>
            </tr>
            <tr>
              <tr>
                <td>Status Pembayaran<span style="color:red;">*</span></td>
                <td>
                  <select class="form-control form-control-sm inputtext walikelas" name="is_lunas">
                      <option value="N" @if($data->is_lunas == "N") selected @endif>
                      PROSES
                      </option>
                      <option value="Y" @if($data->is_lunas == "Y") selected @endif>
                        LUNAS
                      </option>
                    </select>
                </td>
              </tr>
              <tr>
                <td>Pegawai <span style="color:red;">*</span></td>
                <td>
                  <select class="form-control form-control-sm inputtext" name="pegawai_id">
                    <option disabled selected value>Pilih</option>
                    @foreach($employees as $employee)
                      <option value="<?= $employee->id ?>" @if($employee->id == $data->pegawai_id) selected @endif>
                        <?= $employee->nama_lengkap ?>
                      </option>
                      @endforeach
                    </select>
                </td>
              </tr>
          <button class="btn btn-success mt-3" id="simpan" type="submit">Simpan Data</button>
        </form>
        </div>
      </div>
    </div>
    </div>

  </div>
</div>

                  </div>
                </div>
    </div>
  </div>
</div>
<!-- content-wrapper ends -->
@endsection

@section('extra_script')
<script>
  if("{{Session::has('success')}}"){
    iziToast.success({
  icon: 'fa fa-save',
  message: "{{Session::get('success')}}",
});
}
</script>
@endsection

