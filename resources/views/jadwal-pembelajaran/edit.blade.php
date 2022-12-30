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

                <select class="form-control @if($errors->has('jadwal_hari')) is-invalid @endif" name="jadwal_hari" >
                <option disabled selected value>Pilih Hari</option>

                  <option value="Senin" @if($detail->jadwal_hari == "Senin") selected @endif>
                   Senin
                  </option>
                  <option value="Selasa" @if($detail->jadwal_hari == "Selasa") selected @endif>
                   Selasa
                  </option><option value="Rabu" @if($detail->jadwal_hari == "Rabu") selected @endif>
                   Rabu
                  </option>
                  </option><option value="Kamis" @if($detail->jadwal_hari == "Kamis") selected @endif>
                   Kamis
                  </option>
                  </option><option value="Jumat" @if($detail->jadwal_hari == "Jumat") selected @endif>
                   Jumat
                  </option>
                </select>
             
              </td>
            </tr>
            <tr>
              <td>Jadwal Waktu <span style="color:red;">*</span></td>
              <td>
                <input type="time" class="form-control form-control-sm inputtext jadwal_waktu_mulai @if($errors->has('jadwal_waktu_mulai')) is-invalid @endif" value="{{$detail->jadwal_waktu_mulai}}" name="jadwal_waktu_mulai">
              </td>
            </tr>
            <tr>
              <td>Jadwal Akhir <span style="color:red;">*</span></td>
              <td>
                <input type="time" class="form-control form-control-sm inputtext jadwal_waktu_akhir @if($errors->has('jadwal_waktu_akhir')) is-invalid @endif" value="{{$detail->jadwal_waktu_akhir}}" name="jadwal_waktu_akhir">
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

