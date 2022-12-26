@extends('main')
@section('content')

@include('dompetdigital.tambah')
<style type="text/css">

</style>
<!-- partial -->
<div class="content-wrapper">
  <div class="row">
    <div class="col-lg-12">
      <nav aria-label="breadcrumb" role="navigation">
        <ol class="breadcrumb bg-info">
          <li class="breadcrumb-item"><i class="fa fa-home"></i>&nbsp;<a href="{{url('admin/home')}}">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">Dompet Digital</li>
        </ol>
      </nav>
    </div>
  	<div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Dompet Digital</h4>
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
                                <th>Saldo</th>
                                <th>Action</th>
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
           'pdf', 'print'
        ],
        ajax: {
            url:'{{ url('/admin/dompetdigitaltable') }}?id={{Auth::user()->id}}',
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
          {data: 'saldo', name: 'saldo'},
          {data: 'aksi', name: 'aksi'},

        ]
  });

  function topup(id) {
      $('.id').val(id);

      $('#tambah').modal('show');

      const gallery = document.querySelectorAll("img")
      gallery.forEach(image => {
         let src = image.getAttribute('src')
         image.addEventListener('click', function () {
             window.open(src)
         });
      });
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
                    "style": "height: 100px; width:100px; border-radius: 0px; cursor: pointer;",
                }).appendTo(image_holder);
                $('.save').attr('disabled', false);
            }, 2000)
        }
        image_holder.show();
        reader.readAsDataURL($(this)[0].files[0]);

        const gallery = document.querySelectorAll("img")
        gallery.forEach(image => {
           let src = image.getAttribute('src')
           image.addEventListener('click', function () {
               window.open(src)
           });
        });
        // waitingDialog.hide();
    } else {
        // waitingDialog.hide();
        alert("This browser does not support FileReader.");
    }
  });


    $('#simpan').click(function(){

      var formdata = new FormData();
      formdata.append('image', $('.uploadGambar')[0].files[0]);

      $.ajax({
        type: "post",
        url: baseUrl + 'admin/topupdompetdigital?_token='+"{{csrf_token()}}&"+$('.table_modal :input').serialize(),
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
                message: 'Data Gagal disimpan!',
            });
          }else if (data.status == 3){
            iziToast.success({
                icon: 'fa fa-save',
                message: 'Data Berhasil Diubah!',
            });
            reloadall();
          }else if (data.status == 4){
            iziToast.warning({
                icon: 'fa fa-info',
                message: 'Data Gagal Diubah!',
            });
          } else if (data.status == 7){
            iziToast.warning({
                icon: 'fa fa-info',
                message: data.message,
            });
          }
        }
      });
    })

    function reloadall() {
      $('.table_modal :input').val("");
      $('.image-holder').empty();
      $('#tambah').modal('hide');
      table.ajax.reload();
    }

    $('#tambah').on('hidden.bs.modal', function (e) {
      reloadall()
    })
</script>
@endsection
