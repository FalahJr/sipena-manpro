@extends('main')
@section('content')

<style type="text/css">

</style>
<!-- partial -->
<div class="content-wrapper">
  <div class="row">
    <div class="col-lg-12">
      <nav aria-label="breadcrumb" role="navigation">
        <ol class="breadcrumb bg-info">
          <li class="breadcrumb-item"><i class="fa fa-home"></i>&nbsp;<a href="{{url('admin/home')}}">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">Pengajuan Top Up</li>
        </ol>
      </nav>
    </div>
  	<div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Pengajuan Top Up</h4>
                    <div class="col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 15px;text-align:right">
                      <!-- <button type="button" class="btn btn-info" data-toggle="modal" data-target="#tambah"><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah Data</button> -->
                    </div>
                    <div class="table-responsive">
        				        <table class="table table_status table-hover " id="table-data" cellspacing="0">
                            <thead class="bg-gradient-info">
                              <tr>
                                <th style="width:15px">No</th>
                                <th>Nama User</th>
                                <th>Role</th>
                                <th>Nominal</th>
                                <th>Bukti Transfer</th>
                                <th>Status</th>
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

var table = $('#table-data').DataTable({
        processing: true,
        // responsive:true,
        serverSide: true,
        searching: true,
        paging: true,
        dom: 'Bfrtip',
        title: '',
        buttons: [
          'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        ajax: {
            url:'{{ url('/admin/approvedompetdigitaltable') }}',
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
            ],
        "columns": [
          {data: 'DT_Row_Index', name: 'DT_Row_Index'},
          {data: 'nama_lengkap', name: 'nama_lengkap'},
          {data: 'rolenama', name: 'rolenama'},
          {data: 'nominal', name: 'saldo'},
          {data: 'image', name: 'image'},
          {data: 'status', name: 'status'},
          {data: 'aksi', name: 'aksi'},
        ]
  });

  function terima(id) {
      iziToast.question({
        close: false,
    		overlay: true,
    		displayMode: 'once',
    		title: 'Terima data',
    		message: 'Apakah anda yakin ?',
    		position: 'center',
    		buttons: [
    			['<button><b>Ya</b></button>', function (instance, toast) {
            $.ajax({
              url:baseUrl + 'admin/actionapprovedompetdigital',
              data:{id, status: "approve"},
              dataType:'json',
              success:function(data){
                if (data.status == 1) {
                  iziToast.success({
                      icon: 'fa fa-save',
                      message: 'Data Berhasil Diterima!',
                  });
                  reloadall();
                }else if(data.status == 2){
                  iziToast.warning({
                      icon: 'fa fa-info',
                      message: 'Data Gagal Diterima!',
                  });
                }

                reloadall();
              }
            });
    			}, true],
    			['<button>Tidak</button>', function (instance, toast) {
    				instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');
    			}],
    		]
    	});
    }

  function tolak(id) {
      iziToast.question({
        close: false,
    		overlay: true,
    		displayMode: 'once',
    		title: 'Tolak data',
    		message: 'Apakah anda yakin ?',
    		position: 'center',
    		buttons: [
    			['<button><b>Ya</b></button>', function (instance, toast) {
            $.ajax({
              url:baseUrl + 'admin/actionapprovedompetdigital',
              data:{id, status: "reject"},
              dataType:'json',
              success:function(data){
                if (data.status == 1) {
                  iziToast.success({
                      icon: 'fa fa-save',
                      message: 'Data Berhasil Ditolak!',
                  });
                  reloadall();
                }else if(data.status == 2){
                  iziToast.warning({
                      icon: 'fa fa-info',
                      message: 'Data Gagal Ditolak!',
                  });
                }

                reloadall();
              }
            });
    			}, true],
    			['<button>Tidak</button>', function (instance, toast) {
    				instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');
    			}],
    		]
    	});
    }

  $(document).ajaxComplete(function(){
    const gallery = document.querySelectorAll("img")
    gallery.forEach(image => {
       let src = image.getAttribute('src')
       image.addEventListener('click', function () {
           window.open(src)
       });
    });
  })

  function reloadall() {
    $('.table_modal :input').val("");
    $('.image-holder').empty();
    $('#tambah').modal('hide');
    table.ajax.reload();
  }
</script>
@endsection
