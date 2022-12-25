@extends('main')
@section('content')

@include('siswa.tambah')
<style type="text/css">

</style>
<!-- partial -->
<div class="content-wrapper">
  <div class="row">
    <div class="col-lg-12">
      <nav aria-label="breadcrumb" role="navigation">
        <ol class="breadcrumb bg-info">
          <li class="breadcrumb-item"><i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">siswa</li>
        </ol>
      </nav>
    </div>
  	<div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Siswa</h4>
                    <div class="col-md-12 col-sm-12 col-xs-12" align="right" style="margin-bottom: 15px;">
                      {{-- @if(Auth::user()->akses('MASTER DATA STATUS','tambah')) --}}
                      
                      @if(Auth::user()->role_id == 1 || DB::table("pegawai")->where("is_tata_usaha","Y")->where("user_id",Auth::user()->id)->get()->isNotEmpty())

                      <button type="button" class="btn btn-info" onclick="showcreate()"><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah Data</button>
@endif
                    </div>
                    <div class="table-responsive">
        				        <table class="table table_status table-hover display nowrap" width="100%" id="table-data" cellspacing="0">
                            <thead class="bg-gradient-info">
                              <tr>
                                <th>No</th>
                                <th>Foto</th>
                                <th>Wali Murid</th>
                                <th>NISN</th>
                                <th>Nama Lengkap</th>
                                <!-- <th>Email</th> -->
                                <th>Tempat, Tanggal Lahir</th>
                                <th>Telepon</th>
                                <th>Alamat</th>
                                <th>Agama</th>
                                <th>Jenis Kelamin</th>
                                <th>Osis</th>
                                <th>Tanggal Daftar</th>
                                <th>Kelas</th>
                                <th>Kartu Digital</th>
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

baseUrlChange += "/admin/siswa";

var table = $('#table-data').DataTable({
        processing: true,
        // responsive:true,
        serverSide: true,
        searching: true,
        paging: true,
        dom: 'Bfrtip',
        title: '',
        "autoWidth": false,
        buttons: [
            // 'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        ajax: {
            url:'{{ url('admin/siswa/table') }}',
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
              {
                 targets: 8,
                 className: 'center'
              },
              {
                 targets: 9,
                 className: 'center'
              },
              {
                 targets: 10,
                 className: 'center'
              },
              {
                 targets: 11,
                 className: 'center'
              },
              {
                 targets: 12,
                 className: 'center'
              },
              {
                 targets: 13,
                 className: 'center',
                 
              },
              {
                 targets: 14,
                 className: 'center',
                 visible : {{json_encode(Auth::user()->role_id == 1 || DB::table("pegawai")->where("is_tata_usaha","Y")->where("user_id",Auth::user()->id)->get()->isNotEmpty() ? true : false)}}

              },
            ],
        "columns": [
          {data: 'DT_Row_Index', name: 'DT_Row_Index'},
          {data: 'foto_profil', name: 'foto_profil'},
          {data: 'wali_murid', name: 'wali_murid'},
          {data: 'nisn', name: 'nisn'},
          {data: 'nama_lengkap', name: 'nama_lengkap'},
          // {data: 'email', name: 'email'},
          {data: 'tempat_tanggal_lahir', name: 'tempat_tanggal_lahir'},
          {data: 'phone', name: 'phone'},
          {data: 'alamat', name: 'alamat'},
          {data: 'agama', name: 'agama'},
          {data: 'jenis_kelamin', name: 'jenis_kelamin'},
          {data: 'osis', name: 'osis'},
          {data: 'tanggal_daftar', name: 'tanggal_daftar'},
          {data: 'kelas', name: 'kelas'},
          {data: 'kartu_digital', name: 'kartu_digital'},
          {data: 'aksi', name: 'aksi'},
        ]
  });

  $('#simpan').click(function(){

    var formdata = new FormData();
    formdata.append('image', $('.uploadGambar')[0].files[0]);
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
            message: 'Data Berhasil Disimpan!',
        });
        reloadall();
      }else if(data.status == 2){
        iziToast.warning({
            icon: 'fa fa-info',
            message: 'Data Gagal Disimpan, Silahkan cek koneksi internet anda',
        });
        console.log(data.message);
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
