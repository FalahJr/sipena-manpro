@extends('main')
@section('content')
<div class="content-wrapper">
  <div class="row">
    <div class="col-lg-12">
      <nav aria-label="breadcrumb" role="navigation">
        <ol class="breadcrumb bg-info">
          <li class="breadcrumb-item"><i class="fa fa-home"></i>&nbsp;<a href="{{url('admin/bayar-kantin')}}">kantin</a></li>
          <li class="breadcrumb-item active" aria-current="page">Edit Data</li>
        </ol>
      </nav>
    </div>
  	<div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            
           <h4 class="card-title">Edit Data Kantin</h4>
           
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
          
          <form action="{{url('admin/bayar-kantin/update')}}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
          <tr>
            <td>Nama Kantin <span style="color:red;">*</span></td>
            <td>
              <input type="text" class="form-control form-control-sm inputtext nama @if($errors->has('nama')) is-invalid @endif" value="{{$data->nama}}" name="nama">
              <input type="hidden" class="form-control form-control-sm id" value="{{$data->id}}" name="id">
            </td>
          </tr>
          <tr>
            <td>Pilih Pegawai</td>
            <td>
              <select class="form-control form-control-sm inputtext walikelas" name="pegawai_id">
                <option disabled selected value>Pilih</option>
                  <?php foreach($items as $item){ ?>
                  <option value="<?= $item->id ?>" @if($item->id == $pegawai_id->id) selected @endif>
                    <?= $item->nama_lengkap ?>
                  </option>
                  <?php }?>
                </select>
            </td>
          </tr>
          {{-- <tr>
            <td>Image</td>
            <br>
            <img src="{{asset($data->foto)}}" style="height: 80px; width:80px; border-radius: 0px;" class="img-responsive">
            <td>
              <input type="file" class="form-control form-control-sm uploadGambar" name="image" accept="image/*">
            </td>
          </tr> --}}
          <tr>
            <td>Image</td>
            <br>
            <img src="{{asset($data->foto)}}" style="height: 80px; width:80px; border-radius: 0px;" class="img-responsive">
            <td>
              <input type="file" class="form-control form-control-sm uploadGambar" name="foto" accept="image/*">
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

