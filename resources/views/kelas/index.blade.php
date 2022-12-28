@extends('main')
@section('content')

@include('kelas.tambah')
<style type="text/css">

</style>
<!-- partial -->
<div class="content-wrapper">
  <div class="row">
    <div class="col-lg-12">
      <nav aria-label="breadcrumb" role="navigation">
        <ol class="breadcrumb bg-info">
          <li class="breadcrumb-item"><i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">Kelas</li>
        </ol>
      </nav>
    </div>
    <div class="col-lg-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Data Kelas</h4>
          <div class="col-md-12 col-sm-12 col-xs-12" align="right" style="margin-bottom: 15px;">
            {{-- @if(Auth::user()->akses('MASTER DATA STATUS','tambah')) --}}
            @if(Auth::user()->role_id == 1 || DB::table("pegawai")->where("is_tata_usaha","Y")->where("user_id",Auth::user()->id)->get()->isNotEmpty())
            <button type="button" class="btn btn-info" onclick="showcreate()"><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah Data </button>
            @endif
            {{-- @endif --}}
          </div>
          <div class="table-responsive">
            <table class="table table_status table-hover " id="table-data" cellspacing="0">
              <thead class="bg-gradient-info">
                <tr>
                  <th>No</th>
                  <th>Nama</th>
                  <th>Walikelas</th>
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

  baseUrlChange += "/admin/kelas";

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
      url: '{{ url('admin/kelas/table') }}',
  },
    columnDefs: [

    {
      targets: 0,
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
      className: 'center',
      visible : {{json_encode(Auth::user()->role_id == 1 || DB::table("pegawai")->where("is_tata_usaha","Y")->where("user_id",Auth::user()->id)->get()->isNotEmpty() ? true : false)}}

    },
    // {
    //    targets: 6,
    //    className: 'center'
    // },
  ],
    "columns": [
    { data: 'DT_Row_Index', name: 'DT_Row_Index' },
    // {data: 'image', name: 'image'},
    { data: 'nama', name: 'nama' },
    { data: 'guru', name: 'guru' },

    { data: 'aksi', name: 'aksi' },
  ]
  });

  $('#simpan').click(function () {

    var formdata = new FormData();
    // formdata.append('image', $('.uploadGambar')[0].files[0]);
    // var data = 
    $.ajax({
      type: "post",
      url: baseUrlChange + '/simpan?_token=' + "{{csrf_token()}}&" + $('.table_modal .inputtext' ).serialize(),
      data: formdata,
      processData: false, //important
      contentType: false,
      cache: false,
      success: function (data) {
        if (data.status == 1) {
          iziToast.success({
            icon: 'fa fa-save',
            message: 'Data Berhasil Disimpan!',
          });
          reloadall();
        } else if (data.status == 2) {
          iziToast.warning({
            icon: 'fa fa-info',
            message: data.message,
          });
        } else if (data.status == 3) {
          iziToast.success({
            icon: 'fa fa-save',
            message: 'Data Berhasil di Perbarui ! !',
          });
          reloadall();
        } else if (data.status == 4) {
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
  if ("{{Session::has('success')}}") {
    iziToast.success({
      icon: 'fa fa-trash',
      message: "{{Session::get('success')}}",
    });
  }

  function showcreate() {
    $('.table_modal .inputtext').val("");
    $('.image-holder').empty();
    $('.role').val('').change();
    $('.gender').val('').change();
    table.ajax.reload();

    $('#tambah').modal('show');
  }

  function reloadall() {
    location.reload();
    $('.table_modal .inputtext').val("");
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
        image_holder.html('<img src="{{ asset('assets / demo / images / loading.gif') }}" class="img-responsive">');
        $('.save').attr('disabled', true);
        setTimeout(function () {
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