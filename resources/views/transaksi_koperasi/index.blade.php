@extends('main')
@section('content')
@include('transaksi_koperasi.tambah')
{{-- @include('transaksi_koperasi.show') --}}
<style type="text/css">
  .dataTables_filter label {
      margin-bottom: 1.4rem !important;
  }
.dataTables_filter label {
      margin-bottom: 1.4rem !important;
  }
  </style>
<div class="content-wrapper">
  <div class="row">
    <div class="col-lg-12">
      <nav aria-label="breadcrumb" role="navigation">
        <ol class="breadcrumb bg-info">
          <li class="breadcrumb-item"><i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">Transaksi Koperasi</li>
        </ol>
      </nav>
    </div>
  	<div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <div class="col-md-12 col-sm-12 col-xs-12 m-0 p-0 row justify-content-between">
                      <div class="col-12 col-md-3">
                        <h4 class="card-title">Data Tansaksi Koperasi</h4>
                      </div>
                      {{-- @if(Auth::user()->akses('MASTER DATA STATUS','tambah')) --}}
                      <div class="col-12 col-md-5 p-0 text-right">
                        <button type="button" class="btn btn-info" onclick="showcreate()"><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah Transaksi</button>
                      </div>
                      </div>
                    <div class="table-responsive">
        				        <table class="table table_status table-hover " id="table-data" cellspacing="0">
                            <thead class="bg-gradient-info">
                              <tr>
                                <th>No</th>
                                <th>List Penjualan</th>
                                <th>Total Pembayaran</th>
                                <th>Status</th>
                                <th>QRCode</th>
                                <th>Pegawai</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                              </tr>
                            </thead>

                            <tbody>

                            </tbody>


                        </table>
                    </div>
                  </div>
                </div>
    </div>
  </div>
</div>

<div class="modal" id="mymodal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <i class="fa fa-spinner fa-spin"></i>
      </div>
    </div>
  </div>
</div>
<!-- content-wrapper ends -->
@endsection
@section('extra_script')
<script>

baseUrlChange += "/admin/transaksi-koperasi";

var table = $('#table-data').DataTable({
        processing: true,
        // responsive:true,
        serverSide: true,
        searching: true,
        paging: true,
        dom: 'Bfrtip',
        title: '',
        buttons: [
            // 'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        ajax: {
            url:'{{ url('admin/transaksi-koperasi/table') }}',
        },
        columnDefs: [

              {
                 targets: 0 ,
                 className: 'center id'
              },
              {
                 targets: 1,
                 className: 'center'
              },
              {
                 targets: 2,
                 className: 'center'
              },
              {
                 targets: 3,
                 className: 'center'
              },
              {
                 targets: 4,
                 className: 'center'
              },
              {
                 targets: 5,
                 className: 'center'
              },
              {
                 targets: 6,
                 className: 'center'
              },
              {
                 targets: 7,
                 className: 'center'
              },
            ],
        "columns": [
          {data: 'DT_Row_Index', name: 'DT_Row_Index'},
          {data: 'penjualan', name: 'penjualan'},
          {data: 'total_pembayaran', name: 'total_pembayaran'},
          {data: 'is_lunas', name: 'is_lunas'},
          {data: 'qr_code', name: 'qr_code'},
          {data: 'pegawai_id', name: 'pegawai_id'},
          {data: 'tanggal', name: 'tanggal'},
          {data: 'aksi', name: 'aksi'},
        ]
  });

    $('#simpan').click(function(){

    var formdata = new FormData();
    // formdata.append('image', $('.uploadGambar')[0].files[0]);
    // console.log(formdata);
    $.ajax({
      type: "post",
      url: baseUrlChange + '/simpan?_token='+"{{csrf_token()}}&"+$('.modal-body :input').serialize(),
      data: formdata,
      processData: false, //important
      contentType: false,
      cache: false,
      success:function(data){
        if (data.status == 1) {
          iziToast.success({
              icon: 'fa fa-save',
              message: 'Data Berhasil Disimpan!',
          });
          reloadall();
        }else if(data.status == 2){
          iziToast.warning({
              icon: 'fa fa-info',
              message: 'Data Gagal Disimpan, Silahkan cek koneksi internet anda',
          });
        }else if (data.status == 3){
          iziToast.success({
              icon: 'fa fa-save',
              message: 'Data Berhasil di Perbarui ! !',
          });
          reloadall();
        }else if (data.status == 4){
          iziToast.warning({
              icon: 'fa fa-info',
              message: 'Data Gagal di Perbarui !!',
          });
        } else if (data.status == 7) {
          iziToast.warning({
              icon: 'fa fa-info',
              message: data.message,
          });
        }

      }
    });
  })

  //show pop up jika data berhasil di hapus
  if("{{Session::has('success')}}"){
    iziToast.success({
  icon: 'fa fa-trash',
  message: "{{Session::get('success')}}",
  });
  }

    jQuery(document).ready(function($) {
  $('#mymodal').on('show.bs.modal',function(e){
      var button = $(e.relatedTarget);
      var modal = $(this);
      modal.find('.modal-body').load(button.data('remote'));
      modal.find('.modal-title').html(button.data('title'));
  });
  });

    function showcreate() {
      $('.table_modal :input').val("");
      $('.image-holder').empty();
      $('.role').val('').change();
      $('.gender').val('').change();
      table.ajax.reload();
      $('#tambah').modal('show');

      var i=1;  
      $('#add').click(function(){  
          i++; 
          var str = '<tr class="row d-flex ml-0" id="row'+i+'"><td class="col-6 pr-2"><select class="form-control form-control-sm inputtext select2" name="koperasi_list_id[]"><option disabled selected value>Pilih Barang Koperasi</option>';
          var cooperatives = {!! $cooperatives->toJson() !!};
          cooperatives.forEach(function(cooperative) {
            str += '<option value="'+cooperative.id+'">'+cooperative.nama+' || '+cooperative.harga+'</option>';
          });
          $('#dynamic_field').append(str+'</select></td><td class="col-3 pl-2 pr-4"><input type="number" min="1" value="1" class="form-control form-control-sm" name="jumlah_pembelian[]"></td><td class="col-2 pl-1"><button type="button" name="remove" id="'+i+'" class="btn btn-danger w-100 btn_remove"><i class="mdi mdi-close menu-icon"></i></button></td></tr>'); 
          $('.select2').select2();
      }); 

      $(document).on('click', '.btn_remove', function(){    
           var button_id = $(this).attr("id");     
           $('#row'+button_id+'').remove();    
      });    

    }

    function reloadall() {
      $('.table_modal :input').val("");
      $('.image-holder').empty();
      $('#tambah').modal('hide');
      $('.role').val('').change();
      $('.gender').val('').change();
      // // $('#table_modal :input').val('');
      // $(".inputtext").val("");
      // var table1 = $('#table_modal').DataTable();
      // table1.ajax.reload();
      table.ajax.reload();
    }


    $(".uploadGambar").on('change', function () {
            $('.save').attr('disabled', false);
            // waitingDialog.show();
          if (typeof (FileReader) != "undefined") {
              var image_holder = $(".image-holder");
              image_holder.empty();
              var reader = new FileReader();
              reader.onload = function (e) {
                  image_holder.html('<img src="{{ asset('assets/demo/images/loading.gif') }}" class="img-responsive">');
                  $('.save').attr('disabled', true);
                  setTimeout(function(){
                      image_holder.empty();
                      $("<img />", {
                          "src": e.target.result,
                          "class": "thumb-image img-responsive",
                          "style": "height: 100px; width:100px; border-radius: 0px;",
                      }).appendTo(image_holder);
                      $('.save').attr('disabled', false);
                  }, 2000)
              }
              image_holder.show();
              reader.readAsDataURL($(this)[0].files[0]);

              // waitingDialog.hide();
          } else {
              // waitingDialog.hide();
              alert("This browser does not support FileReader.");
          }
      });
  </script>
@endsection
