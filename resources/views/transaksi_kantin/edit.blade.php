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
            <td>Tanggal Pembelian</td>
            <td>
              <input type="date" class="form-control form-control-sm inputtext namaPembeli" value="{{$data->tanggal_pembelian}}" name="tanggal_pembelian">
              <input type="hidden" class="form-control form-control-sm inputtext namaPembeli" value="{{$data->id}}" name="id">
            </td>
          </tr>
            <tr>
              <td>Metode Pembayaran</td>
              <td>
                <select name="is_cash">
                  <option value="Y" @if($data->is_cash == "Y") selected @endif>Cash</option>
                  <option value="N" @if($data->is_cash == "N") selected @endif>Non-Cash</option>
                </select>
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

