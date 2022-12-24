@extends('main')
@section('content')
<div class="content-wrapper">
  <div class="row">
    <div class="col-lg-12">
      <nav aria-label="breadcrumb" role="navigation">
        <ol class="breadcrumb bg-info">
          <li class="breadcrumb-item"><i class="fa fa-home"></i>&nbsp;<a href="{{url('admin/kegiatan-osis')}}">Kegiatan OSIS</a></li>
          <li class="breadcrumb-item active" aria-current="page">Edit Data</li>
        </ol>
      </nav>
    </div>
  	<div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            
           <h4 class="card-title">Edit Kegiatan</h4>
           
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
          <form action="{{url('admin/kegiatan-osis/update')}}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type="hidden" class="form-control form-control-sm id" value="{{$data->id}}" name="id">
            <tr>
              <td>Kegiatan <span style="color:red;">*</span></td>
              <td>
                <input type="text" class="form-control form-control-sm inputtext @if($errors->has('kegiatan')) is-invalid @endif kegiatan" value="{{$data->kegiatan}}" name="kegiatan">
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
                <input type="date" class="form-control form-control-sm inputtext @if($errors->has('tanggal')) is-invalid @endif tanggal" value="{{$data->tanggal}}" name="tanggal">
              </td>
            </tr>
            <tr>
              <td>Pelaksana<span style="color:red;">*</span></td>
              <td>
                <select class="form-control form-control-sm inputtext @if($errors->has('pelaksana')) is-invalid @endif" value="{{$data->pelaksana}}" name="pelaksana">
                  <option >{{$data->pelaksana}}</option>
                  @foreach($students as $student)
                    <option value="<?= $student->nama_lengkap ?>">
                      <?= $student->nama_lengkap ?>
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

