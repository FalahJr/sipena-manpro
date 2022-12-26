@extends('main')
@section('content')

@include('list_koperasi.tambah')
<style type="text/css">
  .dataTables_filter label {
      margin-bottom: 1.4rem !important;
  }
.dataTables_filter label {
      margin-bottom: 1.4rem !important;
  }
  </style>
<!-- partial -->
<div class="content-wrapper">
  <div class="row">
    <div class="col-lg-12">
      <nav aria-label="breadcrumb" role="navigation">
        <ol class="breadcrumb bg-info">
          <li class="breadcrumb-item"><i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">Koperasi Sekolah</li>
        </ol>
      </nav>
    </div>
  	<div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <div class="col-md-12 col-sm-12 col-xs-12 m-0 p-0 row justify-content-between">
                      <div class="col-12 col-md-3">
                        <h4 class="card-title">Data List Barang</h4>
                      </div>
                      {{-- @if(Auth::user()->akses('MASTER DATA STATUS','tambah')) --}}
                      <div class="col-12 col-md-5 p-0 text-right">
                        @if(Auth::user()->role_id == 1 || DB::table("pegawai")->where("is_koperasi","Y")->where("user_id",Auth::user()->id)->get()->isNotEmpty())
                        <button type="button" class="btn btn-info" onclick="showcreate()"><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah List</button>
                        @endif
                      </div>
                    </div>
                    <div class="table-responsive">
        				        <table class="table table_status table-hover " id="table-data" cellspacing="0">
                            <thead class="bg-gradient-info">
                              <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Harga</th>
                                <th>Dibuat Oleh</th>
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
<!-- content-wrapper ends -->
@endsection
@section('extra_script')
<script>

baseUrlChange += "/admin/list-koperasi";

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
            url:'{{ url('admin/list-koperasi/table') }}',
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
                 className: 'center',
                 visible : {{json_encode(Auth::user()->role_id == 1 || DB::table("pegawai")->where("is_koperasi","Y")->where("user_id",Auth::user()->id)->get()->isNotEmpty() ? true : false)}}
              },
            ],
        "columns": [
          {data: 'DT_Row_Index', name: 'DT_Row_Index'},
          {data: 'nama', name: 'nama'},
          {data: 'harga', name: 'harga'},
          {data: 'pegawai_id', name: 'pegawai_id'},
          {data: 'aksi', name: 'aksi'},
        ]
  });

    $('#simpan').click(function(){

    var formdata = new FormData();
    // formdata.append('foto', $('.uploadGambar')[0].files[0]);
    // console.log(formdata);
    $.ajax({
      type: "post",
      url: baseUrlChange + '/simpan?_token='+"{{csrf_token()}}&"+$('.table_modal :input').serialize(),
      data: formdata,
      processData: false, //important
      contentType: false,
      cache: false,
      success:function(data){
        if (data.status == 1) {
          iziToast.success({
              icon: 'fa fa-save',
              message: 'Data Berhasil disimpan',
          });
          reloadall();
        }else if(data.status == 2){
          iziToast.warning({
              icon: 'fa fa-info',
              message: 'Data gagal disimpan , silahkan cek koneksi internet anda!',
          });
        }else if (data.status == 3){
          iziToast.success({
              icon: 'fa fa-save',
              message: 'Data Sukses di perbarui!',
          });
          reloadall();
        }else if (data.status == 4){
          iziToast.warning({
              icon: 'fa fa-info',
              message: 'Data gagal di perbarui!!',
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

    function showcreate() {
      $('.table_modal :input').val("");
      $('.image-holder').empty();
      $('.role').val('').change();
      $('.gender').val('').change();
      table.ajax.reload();

      $('#tambah').modal('show');
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
