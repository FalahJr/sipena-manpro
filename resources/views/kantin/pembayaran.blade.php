@extends('main')
@section('content')
<div class="content-wrapper">
  <div class="row">
    <div class="col-lg-12">
      <nav aria-label="breadcrumb" role="navigation">
        <ol class="breadcrumb bg-info">
          <li class="breadcrumb-item"><i class="fa fa-home"></i>&nbsp;<a href="{{url('admin/transaksi-kantin')}}">Kantin</a></li>
          <li class="breadcrumb-item active" aria-current="page">Pembayaran Kantin</li>
        </ol>
      </nav>
    </div>
  	<div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            
           <h4 class="card-title">Pembayaran Kantin</h4>
           
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
          
          <form action="{{url('admin/bayar-kantin/bayar')}}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <tr>
              <td>Nama Kantin<span style="color:red;">*</span></td>
              <td>
                <input type="hidden" class="form-control form-control-sm id" value="{{$data->id}}" name="kantin_id">
                <input type="text" class="form-control form-control-sm inputtext nama" value="{{$data->nama}}" disabled>
              <input type="hidden" class="form-control form-control-sm id" value="{{Auth::user()->id}}" name="user_id">
              </td>
            </tr>
          <tr>
            <td>Keterangan <span style="color:red;">*</span></td>
            <td>
              <textarea class="form-control form-control-sm deskripsi" name="keterangan" rows="8" cols="80"></textarea>
            </td>
          <tr>
            <tr>
              <td>Total Harga<span style="color:red;">*</span></td>
              <td>
                <input type="text" class="form-control form-control-sm inputtext totalHarga" name="harga_total">
              </td>
            </tr>
          <button class="btn btn-success mt-3" id="simpan" type="submit">Bayar Sekarang</button>
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

