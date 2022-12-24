@extends('main')
@section('content')
<div class="content-wrapper">
  <div class="row">
    <div class="col-lg-12">
      <nav aria-label="breadcrumb" role="navigation">
        <ol class="breadcrumb bg-info">
          <li class="breadcrumb-item"><i class="fa fa-home"></i>&nbsp;<a href="{{url('admin/katalog-buku')}}">Katalog Buku</a></li>
          <li class="breadcrumb-item active" aria-current="page">Edit Data</li>
        </ol>
      </nav>
    </div>
  	<div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            
           <h4 class="card-title">Edit Katalog Buku</h4>
           
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
          
          <form action="{{url('admin/katalog-buku/update')}}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
          <tr>
            <td>Stok Buku <span style="color:red;">*</span></td>
            <td>
              <input type="text" class="form-control form-control-sm inputtext judul @if($errors->has('stok_buku')) is-invalid @endif" value="{{$data->stok_buku}}" name="stok_buku">
              <input type="hidden" class="form-control form-control-sm id" value="{{$data->id}}" name="id">
            </td>
          </tr>
          <tr>
            <td>Judul <span style="color:red;">*</span></td>
            <td>
              <input type="text" class="form-control form-control-sm inputtext judul @if($errors->has('judul')) is-invalid @endif" value="{{$data->judul}}" name="judul">
            </td>
          </tr>
          <tr>
            <td>Author <span style="color:red;">*</span></td>
            <td>
              <input type="text" class="form-control form-control-sm inputtext author @if($errors->has('author')) is-invalid @endif" value="{{$data->author}}" name="author">
            </td>
          </tr>

          <tr>
            <td>Kategori Buku<span style="color:red;">*</span></td>
            <td>
              <select class="form-control form-control-sm inputtext" name="perpus_kategori_id">
                <option disabled selected value>Pilih</option>
                @foreach($categories as $category)
                  <option value="<?= $category->id ?>" @if($category->id == $category_id) selected @endif>
                    <?= $category->nama ?>
                  </option>
                  @endforeach
                </select>
            </td>
          </tr>
         
          <tr>
            <td>Bahasa <span style="color:red;">*</span></td>
            <td>
              <input type="text" class="form-control form-control-sm inputtext bahasa @if($errors->has('bahasa')) is-invalid @endif" value="{{$data->bahasa}}" name="bahasa">
            </td>
          </tr>

          <tr>
            <td>Total Halaman <span style="color:red;">*</span></td>
            <td>
              <input type="text" class="form-control form-control-sm inputtext total_halaman @if($errors->has('total_halaman')) is-invalid @endif" value="{{$data->total_halaman}}" name="total_halaman">
            </td>
          </tr>

          <tr>
            <td>Foto</td>
            <br>
            <img src="{{asset($data->foto)}}" style="height: 80px; width:80px; border-radius: 0px;" class="img-responsive">
            <td>
              <input type="file" class="form-control form-control-sm uploadGambar" name="image" accept="image/*">
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

