@extends('main')
@section('content')

@include('pegawai.tambah')
<style type="text/css">

</style>
<!-- partial -->

<div class="content-wrapper">
  <div class="row">
    <div class="col-lg-12">
      <nav aria-label="breadcrumb" role="navigation">
        <ol class="breadcrumb bg-info">
          <li class="breadcrumb-item"><i class="fa fa-home"></i>&nbsp;<a href="{{url('admin/pegawai')}}">Pegawai</a></li>
          <li class="breadcrumb-item active" aria-current="page">Edit Data</li>
        </ol>
      </nav>
    </div>
  	<div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            
          <?php if($data->is_kantin === "Y"){ ?>
           <h4 class="card-title">Edit Data Pegawai Kantin</h4>
           <?php }else if($data->is_koperasi === "Y"){ ?>
           <h4 class="card-title">Edit Data Pegawai Koperasi</h4>
           <?php }else if($data->is_perpus === "Y"){ ?>
           <h4 class="card-title">Edit Data Pegawai Perpustakaan</h4>
           <?php } else if($data->is_tata_usaha === "Y"){ ?>
           <h4 class="card-title">Edit Data Pegawai Tata Usaha</h4>
           <?php } else if($data->is_pengawas_sekolah === "Y"){ ?>
           <h4 class="card-title">Edit Data Pegawai Pengawas Sekolah</h4>
           <?php } ?>
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
            <td>Nama Lengkap <span style="color:red;">*</span></td>
            <td>
              <input type="text" class="form-control form-control-sm inputtext @if($errors->has('nama_lengkap')) is-invalid @endif" value="{{$data->nama_lengkap}}" name="nama_lengkap">
              <input type="hidden" class="form-control form-control-sm" value="{{$data->id}}" name="id">
            </td>
          </tr>
          <tr>
            <td>Tanggal Lahir</td>
            <td>
              <input type="date" class="form-control form-control-sm inputtext @if($errors->has('tanggal_lahir')) is-invalid @endif" value="{{$data->tanggal_lahir}}" name="tanggal_lahir">
            </td>
          </tr>
          <tr>
            <td>No Hp</td>
            <td>
              <input type="text" class="form-control form-control-sm inputtext @if($errors->has('phone')) is-invalid @endif" value="{{$data->phone}}" name="phone">

            </td>
          </tr>
          <tr>
            <td>Alamat <span style="color:red;">*</span></td>
            <td>
              <textarea class="form-control form-control-sm @if($errors->has('address')) is-invalid @endif" name="address" rows="8" cols="80">{!! htmlspecialchars($data->address) !!}</textarea>
              <!-- <div class="alert alert-warning" role="alert">
              This address will also be used for the shop address (Format: street name and house number (space) sub-district (space) city)
              </div> -->
 
            </td>
          </tr>
          <tr>
            <td>Jenis Kelamin <span style="color:red;">*</span></td>
            <td>
              <select class="form-control @if($errors->has('gender')) is-invalid @endif" name="gender">
                <option value="" selected>- Pilih -</option>
                <option value="L" @if($data->gender == "L") selected @endif> Laki-Laki </option>
                <option value="P" @if($data->gender == "P") selected @endif> Perempuan </option>
    
              </select>
            </td>
          </tr>
          <tr>
            <td>Image</td>
            <td>
              <input type="file" class="form-control form-control-sm profil_picture" name="image" accept="image/*">
            </td>
          </tr>
          {{-- <tr>
            <td>Image</td>
            <td>
              <input type="file" class="form-control form-control-sm uploadGambar" name="profile_picture" accept="image/*">
            </td>
          </tr> --}}
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

