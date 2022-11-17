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
          <li class="breadcrumb-item"><i class="fa fa-home"></i>&nbsp;<a href="{{url('admin/wali-murid')}}">Wali Murid</a></li>
          <li class="breadcrumb-item active" aria-current="page">Edit Data</li>
        </ol>
      </nav>
    </div>
  	<div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            
           <h4 class="card-title">Edit Data Wali Murid</h4>
           
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
          
          <form action="{{url('admin/wali-murid/update')}}" method="POST">
            {{ csrf_field() }}
          <tr>
            <td>Email <span style="color:red;">*</span></td>
            <td>
              <input type="email" class="form-control form-control-sm inputtext @if($errors->has('email')) is-invalid @endif" value="{{$data->email}}" name="email">
              <input type="hidden" class="form-control form-control-sm" value="{{$data->id}}" name="id">
            </td>
          </tr>
          <tr>
            <td>Nama Ayah</td>
            <td>
              <input type="text" class="form-control form-control-sm inputtext @if($errors->has('nama_ayah')) is-invalid @endif" value="{{$data->nama_ayah}}" name="nama_ayah">
            </td>
          </tr>
          <tr>
            <td>Nama Ibu</td>
            <td>
              <input type="text" class="form-control form-control-sm inputtext @if($errors->has('nama_ibu')) is-invalid @endif" value="{{$data->nama_ibu}}" name="nama_ibu">

            </td>
          </tr>
          <tr>
            <td>Tanggal Lahir</td>
            <td>
              <input type="date" class="form-control form-control-sm inputtext tgl_lahir @if($errors->has('tanggal_lahir')) is-invalid @endif" value="{{$data->tanggal_lahir}}" name="tanggal_lahir">
            </td>
          </tr>
          <tr>
            <td>Phone</td>
            <td>
              <input type="text" class="form-control form-control-sm inputtext @if($errors->has('no_telp')) is-invalid @endif" value="{{$data->no_telp}}" name="no_telp">

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

