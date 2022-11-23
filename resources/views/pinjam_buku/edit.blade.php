@extends('main')
@section('content')
<div class="content-wrapper">
  <div class="row">
    <div class="col-lg-12">
      <nav aria-label="breadcrumb" role="navigation">
        <ol class="breadcrumb bg-info">
          <li class="breadcrumb-item"><i class="fa fa-home"></i>&nbsp;<a href="{{url('admin/pinjam-buku')}}">Pinjam Buku</a></li>
          <li class="breadcrumb-item active" aria-current="page">Edit Data</li>
        </ol>
      </nav>
    </div>
  	<div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            
           <h4 class="card-title">Edit Pinjam Buku</h4>
           
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
          <form action="{{url('admin/pinjam-buku/update')}}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type="hidden" class="form-control form-control-sm id" value="{{$data->id}}" name="id">
          <tr>
            <td>Buku<span style="color:red;">*</span></td>
            <td>
              <select class="form-control form-control-sm inputtext" name="perpus_katalog_id[]" multiple="multiple">
                <option >Pilih</option>
                @foreach($items as $item)
                  <option value="<?= $item->id ?>" @if($item->id == $books[0] || $item->id == $books[1] || $item->id == $books[2]) selected @endif>
                    <?= $item->judul ?>
                  </option>
                  @endforeach

                </select>
            </td>
          </tr>

          <tr>
            <td>Tanggal Peminjaman <span style="color:red;">*</span></td>
            <td>
              <input type="date" class="form-control form-control-sm inputtext tanggal_peminjaman @if($errors->has('tanggal_peminjaman')) is-invalid @endif" value="{{$data->tanggal_peminjaman}}" name="tanggal_peminjaman">
            </td>
          </tr>

          <tr>
            <td>Tanggal Pengembalian <span style="color:red;">*</span></td>
            <td>
              <input type="date" class="form-control form-control-sm inputtext tanggal_pengembalian @if($errors->has('tanggal_pengembalian')) is-invalid @endif" value="{{$data->tanggal_pengembalian}}" name="tanggal_pengembalian">
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
