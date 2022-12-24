@extends('main')
@section('content')
<div class="content-wrapper">
  <div class="row">
    <div class="col-lg-12">
      <nav aria-label="breadcrumb" role="navigation">
        <ol class="breadcrumb bg-info">
          <li class="breadcrumb-item"><i class="fa fa-home"></i>&nbsp;<a href="{{url('admin/ekstrakulikuler')}}">Kegiatan Ekstrakulikuler</a></li>
          <li class="breadcrumb-item active" aria-current="page">Edit Data</li>
        </ol>
      </nav>
    </div>
  	<div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            
           <h4 class="card-title">Edit Kegiatan Ekstrakulikuler</h4>
           
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
          <form action="{{url('admin/ekstrakulikuler/update')}}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type="hidden" class="form-control form-control-sm id" value="{{$data->id}}" name="id">
            <tr>
              <td>Nama Kegiatan <span style="color:red;">*</span></td>
              <td>
                <input type="text" class="form-control form-control-sm inputtext @if($errors->has('nama')) is-invalid @endif nama" value="{{$data->nama}}" name="nama">
              </td>
            </tr>
            <tr>
              <td>Jam Mulai <span style="color:red;">*</span></td>
              <td>
                <input type="time" class="form-control form-control-sm inputtext @if($errors->has('jam_mulai')) is-invalid @endif jam_mulai" value="{{$data->jam_mulai}}" name="jam_mulai">
              </td>
            </tr>
            <tr>
              <td>Jadwal Hari <span style="color:red;">*</span></td>
              <td>
                <input type="text" class="form-control form-control-sm inputtext @if($errors->has('jadwal_hari')) is-invalid @endif jadwal_hari" value="{{$data->jadwal_hari}}" name="jadwal_hari">
              </td>
            </tr>
            <tr>
              <td>Guru Penanggung Jawab<span style="color:red;">*</span></td>
              <td>
                <select class="form-control form-control-sm inputtext @if($errors->has('guru_id')) is-invalid @endif" value="{{$data->guru_id}}" name="guru_id">
                  <!-- <option disabled selected value="{{$data->guru_id}}">{{$data->guru_id}}</option> -->
                  @foreach($teacherChoice as $teacherChoices)
                  @if($teacherChoices->id == $data->guru_id)
                  <option  value="<?= $data->guru_id ?>"  selected disabled >
                      <?= $teacherChoices->nama_lengkap ?>
                    </option>
                    @endif
                    @endforeach
                  @foreach($teachers as $teacher)
                    <option value="<?= $teacher->id ?>" @if($teacher->id == $data->guru_id) selected @endif>
                      <?= $teacher->nama_lengkap ?>
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

