@extends('main')
@section('content')
<div class="content-wrapper">
  <div class="row">
    <div class="col-lg-12">
      <nav aria-label="breadcrumb" role="navigation">
        <ol class="breadcrumb bg-info">
          <li class="breadcrumb-item"><i class="fa fa-home"></i>&nbsp;<a href="{{url('admin/pinjam-fasilitas')}}">Pinjam Fasilitas</a></li>
          <li class="breadcrumb-item active" aria-current="page">Edit Data</li>
        </ol>
      </nav>
    </div>
  	<div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            
           <h4 class="card-title">Edit Pinjam Fasilitas Fasilitas</h4>
           
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
          <form action="{{url('admin/pinjam-fasilitas/update')}}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type="hidden" class="form-control form-control-sm id" value="{{$data->id}}" name="id">
            <tr>
              <td>Dipinjam Oleh <span style="color:red;">*</span></td>
              <td>
                <select class="form-control form-control-sm inputtext" name="user_id">
                  <option disabled selected value>Pilih</option>
                  @foreach($users as $user)
                    <option value="<?= $user->id ?>" @if($data->user_id == $user->id) selected @endif>
                      <?= $user->username ?>
                    </option>
                    @endforeach
  
                  </select>
              </td>
            </tr>
            <tr>
              <td>Fasilitas <span style="color:red;">*</span></td>
              <td>
                <select class="form-control form-control-sm inputtext" name="peminjaman_fasilitas_id">
                  <option disabled selected value>Pilih</option>
                  @foreach($facilities as $facility)
                    <option value="<?= $facility->id ?>" @if($data->peminjaman_fasilitas_id == $facility->id) selected @endif>
                      <?= $facility->nama ?>
                    </option>
                    @endforeach
  
                  </select>
              </td>
            </tr>
            <tr>
              <td>Jam Mulai <span style="color:red;">*</span></td>
              <td>
                <input type="time" class="form-control form-control-sm inputtext @if($errors->has('jam_mulai')) is-invalid @endif jam_mulai" value="{{$data->jam_mulai}}" name="jam_mulai">
              </td>
            </tr>
            <tr>
              <td>Jam Selesai <span style="color:red;">*</span></td>
              <td>
                <input type="time" class="form-control form-control-sm inputtext @if($errors->has('jam_selesai')) is-invalid @endif jam_selesai" value="{{$data->jam_selesai}}" name="jam_selesai">
              </td>
            </tr>
            <tr>
              <td>Tanggal <span style="color:red;">*</span></td>
              <td>
                <input type="date" class="form-control form-control-sm inputtext tanggal @if($errors->has('tanggal')) is-invalid @endif" value="{{$data->tanggal}}" name="tanggal">
              </td>
            </tr>
            <tr>
              <td>Dikonfirmasi Oleh<span style="color:red;">*</span></td>
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

