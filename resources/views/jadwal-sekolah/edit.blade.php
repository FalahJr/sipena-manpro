@extends('main')
@section('content')

<style type="text/css">

</style>
<!-- partial -->

<div class="content-wrapper">
  <div class="row">
    <div class="col-lg-12">
      <nav aria-label="breadcrumb" role="navigation">
        <ol class="breadcrumb bg-info">
          <li class="breadcrumb-item"><i class="fa fa-home"></i>&nbsp;<a href="{{url('admin/jadwal-sekolah')}}">Jadwal Sekolah</a></li>
          <li class="breadcrumb-item active" aria-current="page">Edit Data</li>
        </ol>
      </nav>
    </div>
  	<div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            
           <h4 class="card-title">Edit Data Jadwal Sekolah</h4>
           
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
          
          <form action="{{url('admin/jadwal-sekolah/update')}}" method="POST">
            {{ csrf_field() }}
            <tr>
              <td>Kegiatan <span style="color:red;">*</span></td>
              <td>
                <input type="hidden" class="form-control form-control-sm id" value="{{$detail->id}}" name="id">
                <input type="text" class="form-control form-control-sm inputtext jadwal_hari @if($errors->has('kegiatan')) is-invalid @endif" value="{{$detail->kegiatan}}" name="kegiatan">
              </td>
            </tr>
          <tr>
              <td>Jadwal Hari <span style="color:red;">*</span></td>
              <td>
                <input type="text" class="form-control form-control-sm inputtext jadwal_hari @if($errors->has('jadwal_hari')) is-invalid @endif" value="{{$detail->jadwal_hari}}" name="jadwal_hari">
              </td>
            </tr>
            <tr>
              <td>Jam Mulai <span style="color:red;">*</span></td>
              <td>
                <input type="time" class="form-control form-control-sm inputtext @if($errors->has('jam_mulai')) is-invalid @endif jam_mulai" value="{{$detail->jam_mulai}}" name="jam_mulai">
              </td>
            </tr>
            <tr>
              <td>Jam Selesai <span style="color:red;">*</span></td>
              <td>
                <input type="time" class="form-control form-control-sm inputtext @if($errors->has('jam_selesai')) is-invalid @endif jam_selesai" value="{{$detail->jam_selesai}}" name="jam_selesai">
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

