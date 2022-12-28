@extends('main')
@section('content')

@include('mutasisiswa.tambah')
<style type="text/css">

</style>
<!-- partial -->
<div class="content-wrapper">
  <div class="row">
    <div class="col-lg-12">
      <nav aria-label="breadcrumb" role="navigation">
        <ol class="breadcrumb bg-info">
          <li class="breadcrumb-item"><i class="fa fa-home"></i>&nbsp;<a href="{{url('admin/home')}}">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">Mutasi Siswa</li>
        </ol>
      </nav>
    </div>
  	<div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Mutasi Siswa</h4>
                    <div class="col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 15px;text-align:right">
                    @if(Auth::user()->role_id == 3 || Auth::user()->role_id == 1)

                      <button type="button" class="btn btn-info" data-toggle="modal" data-target="#tambah"><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah Data</button>
                      @endif
                    </div>
                    <div class="table-responsive">
        				        <table class="table table_status table-hover " id="table-data" cellspacing="0">
                            <thead class="bg-gradient-info">
                              <tr>
                                <th style="width:15px">No</th>
                                <th>Nama Siswa</th>
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
$(".uploadGambar6").css("display", "none");
$(".image-holder6").css("display", "none");

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
            url:'{{ url('/admin/mutasisiswatable') }}?id={{Auth::user()->id}}',
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
                 className: 'center',
                 visible : {{json_encode(Auth::user()->role_id == 1 || Auth::user()->role_id == 3 ? true : false)}}

              },
            ],
        "columns": [
          {data: 'DT_Row_Index', name: 'DT_Row_Index'},
          {data: 'nama_lengkap', name: 'nama_lengkap'},
          {data: 'status', name: 'status'},
          {data: 'aksi', name: 'aksi'},

        ]
  });

  function edit(id) {
    // body...
    $.ajax({
      url:baseUrl + 'admin/editmutasisiswa',
      data:{id},
      dataType:'json',
      success:function(data){
        $('.id').val(data.id);
        $("#siswa_id").val(data.siswa_id).change();
        $("#status").val(data.status).change();
        $('#status').attr('disabled', 'disabled');

        var image_holder = $(".image-holder");
        image_holder.empty();
        $("<a href="+baseUrl + data.surat_keterangan_pindah_sekolah_asal+" target='_blank'> Lihat File </a>").appendTo(image_holder);

        var image_holder = $(".image-holder1");
        image_holder.empty();
        $("<a href="+baseUrl + data.tanda_bukti_mutasi_dispen_provinsi+" target='_blank'> Lihat File </a>").appendTo(image_holder);

        var image_holder = $(".image-holder2");
        image_holder.empty();
        $("<a href="+baseUrl + data.surat_rekom_penyaluran_dari_deriktorat_jendral_dikdasmen+" target='_blank'> Lihat File </a>").appendTo(image_holder);

        var image_holder = $(".image-holder3");
        image_holder.empty();
        $("<a href="+baseUrl + data.raport_asal+" target='_blank'> Lihat File </a>").appendTo(image_holder);

        var image_holder = $(".image-holder4");
        image_holder.empty();
        $("<a href="+baseUrl + data.fotocoy_raport+" target='_blank'> Lihat File </a>").appendTo(image_holder);

        var image_holder = $(".image-holder5");
        image_holder.empty();
        $("<a href="+baseUrl + data.fotocopy_sertifikat+" target='_blank'> Lihat File </a>").appendTo(image_holder);

        var image_holder = $(".image-holder6");
        image_holder.empty();
        $("<a href="+baseUrl + data.surat_rekomendasi_penerimaan+" target='_blank'> Lihat File </a>").appendTo(image_holder);

        var image_holder = $(".image-holder7");
        image_holder.empty();
        $("<img />", {
            "src":  baseUrl + data.pas_foto,
            "class": "thumb-image img-responsive",
            "style": "height: 100px; width:100px; border-radius: 0px; cursor: pointer;",
        }).appendTo(image_holder);

        $('#tambah').modal('show');

        const gallery = document.querySelectorAll("img")
        gallery.forEach(image => {
           let src = image.getAttribute('src')
           image.addEventListener('click', function () {
               window.open(src)
           });
        });

      }
    });

  }

  $('#simpan').click(function(){

    var formdata = new FormData();
    formdata.append('image', $('.uploadGambar')[0].files[0]);
    formdata.append('image1', $('.uploadGambar1')[0].files[0]);
    formdata.append('image2', $('.uploadGambar2')[0].files[0]);
    formdata.append('image3', $('.uploadGambar3')[0].files[0]);
    formdata.append('image4', $('.uploadGambar4')[0].files[0]);
    formdata.append('image5', $('.uploadGambar5')[0].files[0]);
    formdata.append('image6', $('#uploadGambar6')[0].files[0]);
    formdata.append('image7', $('.uploadGambar7')[0].files[0]);

    $.ajax({
      type: "post",
      url: baseUrl + 'admin/simpanmutasisiswa?_token='+"{{csrf_token()}}&"+$('.table_modal :input').serialize(),
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


  function hapus(id) {
    iziToast.question({
      close: false,
  		overlay: true,
  		displayMode: 'once',
  		title: 'Hapus data',
  		message: 'Apakah anda yakin ?',
  		position: 'center',
  		buttons: [
  			['<button><b>Ya</b></button>', function (instance, toast) {
          $.ajax({
            url:baseUrl + 'admin/hapusmutasisiswa',
            data:{id},
            dataType:'json',
            success:function(data){
              iziToast.success({
                  icon: 'fa fa-trash',
                  message: 'Data Berhasil Dihapus!',
              });

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

  $('#tambah').on('hidden.bs.modal', function (e) {
    reloadall()
  })

  function reloadall() {
    $('#status').removeAttr('disabled');
    $('.table_modal :input').val("");
    $("#siswa_id").val("").change();
    $('.image-holder').empty();
    $('.image-holder1').empty();
    $('.image-holder2').empty();
    $('.image-holder3').empty();
    $('.image-holder4').empty();
    $('.image-holder5').empty();
    $('.image-holder6').empty();
    $('.image-holder7').empty();
    $('#tambah').modal('hide');
    table.ajax.reload();
  }

  $(".uploadGambar7").on('change', function () {
      $('.save').attr('disabled', false);
      // waitingDialog.show();
    if (typeof (FileReader) != "undefined") {
        var image_holder = $(".image-holder7");
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

        // waitingDialog.hide();
    } else {
        // waitingDialog.hide();
        alert("This browser does not support FileReader.");
    }
  });

  $("#status").on("change", function(){
    if(this.value == "MASUK") {
      $(".uploadGambar6").css("display", "none");
      $(".image-holder6").css("display", "none");
    } else if(this.value == "KELUAR") {
      $(".uploadGambar6").css("display", "");
      $(".image-holder6").css("display", "");
    }
  });

</script>
@endsection
