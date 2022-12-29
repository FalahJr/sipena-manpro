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
          
          <form action="{{url('admin/transaksi-kantin')}}" class="col-5" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <tr>
              <td>Nama Kantin</td>
              <td>
                <input type="hidden" class="form-control form-control-sm id" value="{{$data->id}}" name="kantin_id">
                <input type="text" class="form-control form-control-sm inputtext nama" value="{{$data->nama}}" disabled>
              <input type="hidden" class="form-control form-control-sm id" value="{{Auth::user()->id}}" name="user_id">
              </td>
            </tr>
          <tr class="row d-flex">
            <td>Pembelian <span style="color:red;">*</span></td>
            <td>
              <div class="row d-flex mt-1">
              <div class="col-6 pl-3">
                <select class="form-control form-control-sm inputtext w-100 select2" name="kantin_list_id[]">
                  <option disabled selected value>Pilih Menu</option>
                  @foreach($menus as $menu)
                    <option value="<?= $menu->id ?>">
                      <?= $menu->nama ?> || <?= $menu->harga ?>
                    </option>
                    @endforeach
                  </select>
              </div>
              <div class="col-3">
                <input type="number" min="1" value="1" class="form-control form-control-sm" name="jumlah_pembelian[]">
              </div>
              <div class="col-2"><button type="button" name="add" id="add" class="btn btn-info"> <i class="mdi mdi-plus menu-icon"></i></button></div> 
              <div id="dynamic_field"></div>
            </div>
            </td>
          <tr>
            {{-- <tr>
              <td>Total Harga<span style="color:red;">*</span></td>
              <td>
                <input type="text" class="form-control form-control-sm inputtext totalHarga" name="harga_total">
              </td>
            </tr> --}}
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
   var i=1;  
$('#add').click(function(){  
    i++; 
    var str = '<tr class="row my-3 d-flex ml-0" id="row'+i+'"><td class="col-6 pr-4"><select class="form-control form-control-sm inputtext select2" name="kantin_list_id[]"><option disabled selected value>Pilih Menu</option>';
    var menus = {!! $menus->toJson() !!};
    menus.forEach(function(menu) {
      str += '<option value="'+menu.id+'">'+menu.nama+' || '+menu.harga+'</option>';
    });
    $('#dynamic_field').append(str+'</select></td><td class="col-3 pl-2 pr-4"><input type="number" min="1" value="1" class="form-control form-control-sm" name="jumlah_pembelian[]"></td><td class="col-2 pl-2 pr-0"><button type="button" name="remove" id="'+i+'" class="btn btn-danger w-100 btn_remove"><i class="mdi mdi-close menu-icon"></i></button></td></tr>'); 
    $('.select2').select2();
}); 

$(document).on('click', '.btn_remove', function(){    
     var button_id = $(this).attr("id");     
     $('#row'+button_id+'').remove();    
});    

  if("{{Session::has('success')}}"){
    iziToast.success({
  icon: 'fa fa-save',
  message: "{{Session::get('success')}}",
});
}
</script>
@endsection

