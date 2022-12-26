@extends('main')
@section('content')

@include('kategori-keuangan.tambah')
<style type="text/css">
</style>
<!-- partial -->
<div class="content-wrapper">
  <div class="row">
    <div class="col-lg-12">
      <nav aria-label="breadcrumb" role="navigation">
        <ol class="breadcrumb bg-info">
          <li class="breadcrumb-item"><i class="fa fa-home"></i>&nbsp;<a href="{{url('admin/home')}}">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">Kategori Keuangan</li>
        </ol>
      </nav>
    </div>
  	<div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Kategori Keuangan</h4>
                    <div class="col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 15px;text-align:right">
                      <button type="button" class="btn btn-info" data-toggle="modal" data-target="#tambah"><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah Data</button>
                    </div>
                    <div class="table-responsive">
        				        <table class="table table_status table-hover " id="table-data" cellspacing="0">
                            <thead class="bg-gradient-info">
                              <tr>
                                <th style="width:15px">No</th>
                                <th>Nama Kategori</th>
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
            url:'{{ url('/admin/kategori-keuangan-table') }}',
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
            ],
        "columns": [
          {data: 'DT_Row_Index', name: 'DT_Row_Index'},
          {data: 'nama', name: 'nama'},
          {data: 'aksi', name: 'aksi'},

        ]
  });

  function edit(id) {
    // body...
    $.ajax({
      url:baseUrl + 'admin/edit-kategori-keuangan',
      data:{id},
      dataType:'json',
      success:function(data){
        $('.id').val(data.id);
        $("#nama_kategori").val(data.nama).change();

        

        $('#tambah').modal('show');

        // const gallery = document.querySelectorAll("img")
        // gallery.forEach(image => {
        //    let src = image.getAttribute('src')
        //    image.addEventListener('click', function () {
        //        window.open(src)
        //    });
        // });

      }
    });

  }

  $('#simpan').click(function(){
    // 
    var formdata = new FormData();

    $.ajax({
      type: "post",
      url: baseUrl + 'admin/simpan-kategori-keuangan?_token='+"{{csrf_token()}}&"+$('.table_modal :input').serialize(),
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
            url:baseUrl + 'admin/hapus-kategori-keuangan',
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

  $('#tambah').on(function (e) {
    reloadall()
  })

  function reloadall() {
    $('.table_modal :input').val("");
    $('#tambah').modal('hide');

    $("#nama_kategori").val("").change();
   
    table.ajax.reload();
  }

  // $(".uploadGambar").on('change', function () {
  //         $('.save').attr('disabled', false);
  //         // waitingDialog.show();
  //       if (typeof (FileReader) != "undefined") {
  //           var image_holder = $(".image-holder");
  //           image_holder.empty();
  //           var reader = new FileReader();
  //           reader.onload = function (e) {
  //               image_holder.html('<img src="{{ asset('assets/demo/images/loading.gif') }}" class="img-responsive">');
  //               $('.save').attr('disabled', true);
  //               setTimeout(function(){
  //                   image_holder.empty();
  //                   $("<img />", {
  //                       "src": e.target.result,
  //                       "class": "thumb-image img-responsive",
  //                       "style": "height: 100px; width:100px; border-radius: 0px; cursor: pointer;",
  //                   }).appendTo(image_holder);
  //                   $('.save').attr('disabled', false);
  //               }, 2000)
  //           }
  //           image_holder.show();
  //           reader.readAsDataURL($(this)[0].files[0]);

  //           const gallery = document.querySelectorAll("img")
  //           gallery.forEach(image => {
  //              let src = image.getAttribute('src')
  //              image.addEventListener('click', function () {
  //                  window.open(src)
  //              });
  //           });
  //           // waitingDialog.hide();
  //       } else {
  //           // waitingDialog.hide();
  //           alert("This browser does not support FileReader.");
  //       }
  //   });

  //   $(".uploadGambar1").on('change', function () {
  //           $('.save').attr('disabled', false);
  //           // waitingDialog.show();
  //         if (typeof (FileReader) != "undefined") {
  //             var image_holder = $(".image-holder1");
  //             image_holder.empty();
  //             var reader = new FileReader();
  //             reader.onload = function (e) {
  //                 image_holder.html('<img src="{{ asset('assets/demo/images/loading.gif') }}" class="img-responsive">');
  //                 $('.save').attr('disabled', true);
  //                 setTimeout(function(){
  //                     image_holder.empty();
  //                     $("<img />", {
  //                         "src": e.target.result,
  //                         "class": "thumb-image img-responsive",
  //                         "style": "height: 100px; width:100px; border-radius: 0px; cursor: pointer;",
  //                     }).appendTo(image_holder);
  //                     $('.save').attr('disabled', false);
  //                 }, 2000)
  //             }
  //             image_holder.show();
  //             reader.readAsDataURL($(this)[0].files[0]);

  //             const gallery = document.querySelectorAll("img")
  //             gallery.forEach(image => {
  //                let src = image.getAttribute('src')
  //                image.addEventListener('click', function () {
  //                    window.open(src)
  //                });
  //             });
  //             // waitingDialog.hide();
  //         } else {
  //             // waitingDialog.hide();
  //             alert("This browser does not support FileReader.");
  //         }
  //     });

  //     $(".uploadGambar2").on('change', function () {
  //             $('.save').attr('disabled', false);
  //             // waitingDialog.show();
  //           if (typeof (FileReader) != "undefined") {
  //               var image_holder = $(".image-holder2");
  //               image_holder.empty();
  //               var reader = new FileReader();
  //               reader.onload = function (e) {
  //                   image_holder.html('<img src="{{ asset('assets/demo/images/loading.gif') }}" class="img-responsive">');
  //                   $('.save').attr('disabled', true);
  //                   setTimeout(function(){
  //                       image_holder.empty();
  //                       $("<img />", {
  //                           "src": e.target.result,
  //                           "class": "thumb-image img-responsive",
  //                           "style": "height: 100px; width:100px; border-radius: 0px; cursor: pointer;",
  //                       }).appendTo(image_holder);
  //                       $('.save').attr('disabled', false);
  //                   }, 2000)
  //               }
  //               image_holder.show();
  //               reader.readAsDataURL($(this)[0].files[0]);

  //               const gallery = document.querySelectorAll("img")
  //               gallery.forEach(image => {
  //                  let src = image.getAttribute('src')
  //                  image.addEventListener('click', function () {
  //                      window.open(src)
  //                  });
  //               });
  //               // waitingDialog.hide();
  //           } else {
  //               // waitingDialog.hide();
  //               alert("This browser does not support FileReader.");
  //           }
  //       });

  //       $(".uploadGambar3").on('change', function () {
  //               $('.save').attr('disabled', false);
  //               // waitingDialog.show();
  //             if (typeof (FileReader) != "undefined") {
  //                 var image_holder = $(".image-holder3");
  //                 image_holder.empty();
  //                 var reader = new FileReader();
  //                 reader.onload = function (e) {
  //                     image_holder.html('<img src="{{ asset('assets/demo/images/loading.gif') }}" class="img-responsive">');
  //                     $('.save').attr('disabled', true);
  //                     setTimeout(function(){
  //                         image_holder.empty();
  //                         $("<img />", {
  //                             "src": e.target.result,
  //                             "class": "thumb-image img-responsive",
  //                             "style": "height: 100px; width:100px; border-radius: 0px; cursor: pointer;",
  //                         }).appendTo(image_holder);
  //                         $('.save').attr('disabled', false);
  //                     }, 2000)
  //                 }
  //                 image_holder.show();
  //                 reader.readAsDataURL($(this)[0].files[0]);

  //                 const gallery = document.querySelectorAll("img")
  //                 gallery.forEach(image => {
  //                    let src = image.getAttribute('src')
  //                    image.addEventListener('click', function () {
  //                        window.open(src)
  //                    });
  //                 });
  //                 // waitingDialog.hide();
  //             } else {
  //                 // waitingDialog.hide();
  //                 alert("This browser does not support FileReader.");
  //             }
  //         });

  //         $(".uploadGambar4").on('change', function () {
  //                 $('.save').attr('disabled', false);
  //                 // waitingDialog.show();
  //               if (typeof (FileReader) != "undefined") {
  //                   var image_holder = $(".image-holder4");
  //                   image_holder.empty();
  //                   var reader = new FileReader();
  //                   reader.onload = function (e) {
  //                       image_holder.html('<img src="{{ asset('assets/demo/images/loading.gif') }}" class="img-responsive">');
  //                       $('.save').attr('disabled', true);
  //                       setTimeout(function(){
  //                           image_holder.empty();
  //                           $("<img />", {
  //                               "src": e.target.result,
  //                               "class": "thumb-image img-responsive",
  //                               "style": "height: 100px; width:100px; border-radius: 0px; cursor: pointer;",
  //                           }).appendTo(image_holder);
  //                           $('.save').attr('disabled', false);
  //                       }, 2000)
  //                   }
  //                   image_holder.show();
  //                   reader.readAsDataURL($(this)[0].files[0]);

  //                   const gallery = document.querySelectorAll("img")
  //                   gallery.forEach(image => {
  //                      let src = image.getAttribute('src')
  //                      image.addEventListener('click', function () {
  //                          window.open(src)
  //                      });
  //                   });
  //                   // waitingDialog.hide();
  //               } else {
  //                   // waitingDialog.hide();
  //                   alert("This browser does not support FileReader.");
  //               }
  //           });

  //           $(".uploadGambar5").on('change', function () {
  //                   $('.save').attr('disabled', false);
  //                   // waitingDialog.show();
  //                 if (typeof (FileReader) != "undefined") {
  //                     var image_holder = $(".image-holder5");
  //                     image_holder.empty();
  //                     var reader = new FileReader();
  //                     reader.onload = function (e) {
  //                         image_holder.html('<img src="{{ asset('assets/demo/images/loading.gif') }}" class="img-responsive">');
  //                         $('.save').attr('disabled', true);
  //                         setTimeout(function(){
  //                             image_holder.empty();
  //                             $("<img />", {
  //                                 "src": e.target.result,
  //                                 "class": "thumb-image img-responsive",
  //                                 "style": "height: 100px; width:100px; border-radius: 0px; cursor: pointer;",
  //                             }).appendTo(image_holder);
  //                             $('.save').attr('disabled', false);
  //                         }, 2000)
  //                     }
  //                     image_holder.show();
  //                     reader.readAsDataURL($(this)[0].files[0]);

  //                     const gallery = document.querySelectorAll("img")
  //                     gallery.forEach(image => {
  //                        let src = image.getAttribute('src')
  //                        image.addEventListener('click', function () {
  //                            window.open(src)
  //                        });
  //                     });
  //                     // waitingDialog.hide();
  //                 } else {
  //                     // waitingDialog.hide();
  //                     alert("This browser does not support FileReader.");
  //                 }
  //             });

  //             $(".uploadGambar6").on('change', function () {
  //                     $('.save').attr('disabled', false);
  //                     // waitingDialog.show();
  //                   if (typeof (FileReader) != "undefined") {
  //                       var image_holder = $(".image-holder6");
  //                       image_holder.empty();
  //                       var reader = new FileReader();
  //                       reader.onload = function (e) {
  //                           image_holder.html('<img src="{{ asset('assets/demo/images/loading.gif') }}" class="img-responsive">');
  //                           $('.save').attr('disabled', true);
  //                           setTimeout(function(){
  //                               image_holder.empty();
  //                               $("<img />", {
  //                                   "src": e.target.result,
  //                                   "class": "thumb-image img-responsive",
  //                                   "style": "height: 100px; width:100px; border-radius: 0px; cursor: pointer;",
  //                               }).appendTo(image_holder);
  //                               $('.save').attr('disabled', false);
  //                           }, 2000)
  //                       }
  //                       image_holder.show();
  //                       reader.readAsDataURL($(this)[0].files[0]);

  //                       const gallery = document.querySelectorAll("img")
  //                       gallery.forEach(image => {
  //                          let src = image.getAttribute('src')
  //                          image.addEventListener('click', function () {
  //                              window.open(src)
  //                          });
  //                       });
  //                       // waitingDialog.hide();
  //                   } else {
  //                       // waitingDialog.hide();
  //                       alert("This browser does not support FileReader.");
  //                   }
  //               });

</script>
@endsection
