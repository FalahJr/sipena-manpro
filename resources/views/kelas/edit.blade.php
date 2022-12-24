@extends('main')
@section('content')

@include('guru.tambah')
<style type="text/css">

</style>
<!-- partial -->

<div class="content-wrapper">
  <div class="row">
    <div class="col-lg-12">
      <nav aria-label="breadcrumb" role="navigation">
        <ol class="breadcrumb bg-info">
          <li class="breadcrumb-item"><i class="fa fa-home"></i>&nbsp;<a href="{{url('admin/kelas')}}">Kelas</a></li>
          <li class="breadcrumb-item active" aria-current="page">Edit Data</li>
        </ol>
      </nav>
    </div>
  	<div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            
           <h4 class="card-title">Edit Data Kelas</h4>
           
                    <!-- Modal -->
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
          
          <form action="{{url('admin/kelas/update')}}" method="POST">
            {{ csrf_field() }}
          <tr>
            <td>Nama <span style="color:red;">*</span></td>
            <td>
              <input type="text" class="form-control form-control-sm inputtext @if($errors->has('nama')) is-invalid @endif" value="{{$data->nama}}" name="nama">
              <input type="hidden" class="form-control form-control-sm" value="{{$data->id}}" name="id">
            </td>
          </tr>
          <tr>
            <td>Walikelas</td>
            <td>
            <select class="form-control form-control-sm inputtext @if($errors->has('guru_id')) is-invalid @endif" value="{{$data->guru_id}}" name="guru_id">
                  <?php foreach($guru as $walikelas){ ?>

                  <option value="<?= $walikelas->id ?>">
                    <?= $walikelas->nama_lengkap ?>
                  </option>
                  <?php }?>
                </select>
                
    
              </select>
             
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

