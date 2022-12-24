@extends('main')
@section('content')

@include('wali_murid.tambah')
<style type="text/css">

</style>
<!-- partial -->

<div class="content-wrapper">
  <div class="row">
    <div class="col-lg-12">
      <nav aria-label="breadcrumb" role="navigation">
        <ol class="breadcrumb bg-info">
          <li class="breadcrumb-item"><i class="fa fa-home"></i>&nbsp;<a href="{{url('admin/dinas-pendidikan')}}">Dinas Pendidikan</a></li>
          <li class="breadcrumb-item active" aria-current="page">Edit Data</li>
        </ol>
      </nav>
    </div>
  	<div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            
           <h4 class="card-title">Edit Data Dinas Pendidikan</h4>
           
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
          
          <form action="{{url('admin/dinas-pendidikan/update')}}" method="POST" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
          <tr>
            <td>Nama Lengkap <span style="color:red;">*</span></td>
            <td>
              <input type="text" class="form-control form-control-sm inputtext @if($errors->has('nama_lengkap')) is-invalid @endif" value="{{$data->nama_lengkap}}" name="nama_lengkap">
              <input type="hidden" class="form-control form-control-sm" value="{{$data->id}}" name="id">
            </td>
          </tr>
          {{-- <tr>
            <td>Email <span style="color:red;">*</span></td>
            <td>
              <input type="email" class="form-control form-control-sm inputtext @if($errors->has('email')) is-invalid @endif email" value="{{$data->email}}" name="email">
            </td>
          </tr> --}}
          {{-- <tr>
            <td>Tempat Lahir <span style="color:red;">*</span></td>
            <td>
              <input type="text" class="form-control form-control-sm inputtext @if($errors->has('tempat_lahir')) is-invalid @endif tempat_lahir" value="{{$data->tempat_lahir}}" name="tempat_lahir">
            </td>
          </tr> --}}
          <tr>
            <td>Tanggal Lahir</td>
            <td>
              <input type="date" class="form-control form-control-sm inputtext tgl_lahir @if($errors->has('tanggal_lahir')) is-invalid @endif" value="{{$data->tanggal_lahir}}" name="tanggal_lahir">
            </td>
          </tr>
          <tr>
            <td>Phone</td>
            <td>
              <input type="text" class="form-control form-control-sm inputtext @if($errors->has('phone')) is-invalid @endif" value="{{$data->phone}}" name="phone">

            </td>
          </tr>
          <tr>
            <td>Alamat <span style="color:red;">*</span></td>
            <td>
              <textarea class="form-control form-control-sm @if($errors->has('alamat')) is-invalid @endif" name="alamat" rows="8" cols="80">{!! htmlspecialchars($data->alamat) !!}</textarea>

 
            </td>
          </tr>
         
          <tr>
            <td>Jenis Kelamin <span style="color:red;">*</span></td>
            <td>
              <select class="form-control @if($errors->has('jenis_kelamin')) is-invalid @endif" name="jenis_kelamin">
                <option value="" selected>- Pilih -</option>
                <option value="L" @if($data->jenis_kelamin == "L") selected @endif> Laki-Laki </option>
                <option value="P" @if($data->jenis_kelamin == "P") selected @endif> Perempuan </option>
    
              </select>
            </td>
          </tr>
          <tr>
            <td>Foto</td>
            <br>
            <img src="{{asset($data->foto_profil)}}" style="height: 80px; width:80px; border-radius: 0px;" class="img-responsive">
            <td>
              <input type="file" class="form-control form-control-sm profil_picture" name="image" accept="image/*">
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

