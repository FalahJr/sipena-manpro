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
          <li class="breadcrumb-item"><i class="fa fa-home"></i>&nbsp;<a href="{{url('admin/jadwal-pembelajaran')}}">Jadwal Pembelajaran</a></li>
          <li class="breadcrumb-item active" aria-current="page">Edit Data</li>
        </ol>
      </nav>
    </div>
  	<div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            
           <h4 class="card-title">Edit Data Jadwal Pembelajaran</h4>
           
                    <!-- Modal -->
    <!-- Modal content-->
    <div class="row">
      <div class="alert alert-warning" role="alert">
        Silahkan isi semua data yang bertanda<span style="color:red;">*</span>
        </div>
        
    </div>
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
          
          <form action="{{url('admin/jadwal-pembelajaran/update')}}" method="POST">
            {{ csrf_field() }}
            <tr>
              <td>Mata Pelajaran <span style="color:red;">*</span></td>
              <td>
              <select class="form-control @if($errors->has('mapel_id')) is-invalid @endif" name="mapel_id" disabled>
                <option value=<?= $detail->mapel_id ?> selected> 
                <?= $mapelEdit->nama ?>
              </option>
                
    
              </select>
                <input type="hidden" class="form-control form-control-sm id" value="{{$detail->id}}" name="id">
              </td>
            </tr>
            
          <tr>
            <td>Kelas</td>
            <td>
            <select class="form-control @if($errors->has('mapel_id')) is-invalid @endif" name="mapel_id" disabled>
                <option value=<?= $detail->kelas_id ?> selected> 
                <?= $kelas->nama ?>

              </option>
              </select>
            </td>
          </tr>
          <tr>
              <td>Jadwal Hari <span style="color:red;">*</span></td>
              <td>
                <input type="text" class="form-control form-control-sm inputtext jadwal_hari @if($errors->has('jadwal_hari')) is-invalid @endif" value="{{$detail->jadwal_hari}}" name="jadwal_hari">
              </td>
            </tr>
            <tr>
              <td>Jadwal Waktu <span style="color:red;">*</span></td>
              <td>
                <input type="time" class="form-control form-control-sm inputtext jadwal_waktu @if($errors->has('jadwal_waktu')) is-invalid @endif" value="{{$detail->jadwal_waktu}}" name="jadwal_waktu">
              </td>
            </tr>
          <!-- <tr>
            <td align="center" colspan="2">
              <div class="col-md-8 col-sm-6 col-xs-12 image-holder" id="image-holder">

                {{-- <img src="#" class="thumb-image img-responsive" height="100px" alt="image" style="display: none"> --}}

            </div>
            </td>
          </tr> -->
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

