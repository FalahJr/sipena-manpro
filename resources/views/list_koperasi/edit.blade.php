@extends('main')
@section('content')
<div class="content-wrapper">
  <div class="row">
    <div class="col-lg-12">
      <nav aria-label="breadcrumb" role="navigation">
        <ol class="breadcrumb bg-info">
          <li class="breadcrumb-item"><i class="fa fa-home"></i>&nbsp;<a href="{{url('admin/list-koperasi')}}">Koperasi Sekolah</a></li>
          <li class="breadcrumb-item active" aria-current="page">Edit Data</li>
        </ol>
      </nav>
    </div>
  	<div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            
           <h4 class="card-title">Edit Data Koperasi</h4>
           
                    <!-- Modal -->

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
          
          <form action="{{url('admin/list-koperasi/update')}}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
          <tr>
            <td>Nama <span style="color:red;">*</span></td>
            <td>
              <input type="text" class="form-control form-control-sm inputtext nama @if($errors->has('nama')) is-invalid @endif" value="{{$data->nama}}" name="nama">
              <input type="hidden" class="form-control form-control-sm id" value="{{$data->id}}" name="id">
            </td>
          </tr>
          <tr>
            <td>Harga <span style="color:red;">*</span></td>
            <td>
              <input type="text" class="form-control form-control-sm inputtext harga @if($errors->has('harga')) is-invalid @endif" value="{{$data->harga}}" name="harga">
            </td>
          </tr>
          <tr>
            <td>Pilih Pegawai</td>
            <td>
              <select class="form-control form-control-sm inputtext walikelas" name="pegawai_id">
                <option disabled selected value>Pilih</option>
                  <?php foreach($employees as $employee){ ?>
                  <option value="<?= $employee->id ?>" @if($employee->id == $employee_id) selected @endif>
                    <?= $employee->nama_lengkap ?>
                  </option>
                  <?php }?>
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

