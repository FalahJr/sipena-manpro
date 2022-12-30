@extends('main')
@section('content')
<div class="content-wrapper">
  <div class="row">
    <div class="col-lg-12">
      <nav aria-label="breadcrumb" role="navigation">
        <ol class="breadcrumb bg-info">
          <li class="breadcrumb-item"><i class="fa fa-home"></i>&nbsp;<a href="{{url('admin/transaksi-kantin')}}">Transaksi Kantin</a></li>
          <li class="breadcrumb-item active" aria-current="page">Edit Data</li>
        </ol>
      </nav>
    </div>
  	<div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            
           <h4 class="card-title">Edit Transaksi Kantin</h4>
           
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
          
          <form action="{{url('admin/transaksi-kantin/update')}}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <tr>
              <td>Nama Kantin<span style="color:red;">*</span></td>
              <td>
                <input type="hidden" class="form-control form-control-sm id" value="{{$data->id}}" name="id">
                <select class="form-control form-control-sm inputtext walikelas" name="kantin_id" disabled>
                  <option disabled selected value>Pilih</option>
                    <?php foreach($items as $item){ ?>
                    <option value="<?= $item->id ?>" @if($item->id == $kantin_id->id) selected @endif>
                      <?= $item->nama ?>
                    </option>
                    <?php }?>
                  </select>
              </td>
            </tr>
          <tr>
            <td>Nama Pembeli</td>
            <td>
              <input type="text" class="form-control form-control-sm inputtext namaPembeli" value="{{$data->nama_pembeli}}" name="nama_pembeli" disabled>
            </td>
          </tr>
          <tr>
            <td>Keterangan <span style="color:red;">*</span></td>
            <td>
              <textarea class="form-control form-control-sm deskripsi @if($errors->has('keterangan')) is-invalid @endif" value="{{$data->keterangan}}" name="keterangan" rows="8" cols="80">{!! htmlspecialchars($data->keterangan) !!}</textarea>
            </td>
          <tr>
            <tr>
              <td>Total Harga</td>
              <td>
                <input type="text" class="form-control form-control-sm inputtext totalHarga" value="{{$data->harga_total}}" name="total_harga" disabled>
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

