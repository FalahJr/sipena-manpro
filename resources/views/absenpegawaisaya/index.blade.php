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
          <li class="breadcrumb-item active" aria-current="page">Absensi Pegawai Saya</li>
        </ol>
      </nav>
    </div>
  	<div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Absensi Pegawai Saya</h4>
                    <!-- <div class="col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 15px;text-align:right"> -->
                      <!-- <button type="button" class="btn btn-info" data-toggle="modal" data-target="#tambah"><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah Data</button> -->
                    <!-- </div> -->
                    <div class="table-responsive">
        				        <table class="table table_status table-hover " id="table-data" cellspacing="0">
                            <thead class="bg-gradient-info">
                              <tr>
                                <th style="width:15px">No</th>
                                <th>Pegawai</th>
                                <th>Foto</th>
                                <th>Jam Absen</th>
                                <th>Terlambat</th>
                                <th>Izin</th>
                                <th>Alasan</th>
                                <th>Keterangan</th>
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
            url:'{{ url('/admin/absensipegawaitable') }}?id={{Auth::user()->id}}',
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
          {data: 'nama_lengkap', name: 'nama_lengkap'},
          {data: 'image', name: 'image'},
          {data: 'waktu', name: 'waktu  '},
          {data: 'terlambat', name: 'terlambat'},
          {data: 'izin', name: 'izin'},
          {data: 'alasan_izin', name: 'alasan_izin'},
          {data: 'keterangan_izin', name: 'keterangan_izin'},
        ]
  });

  $(document).ajaxComplete(function(){
    const gallery = document.querySelectorAll("img")
    gallery.forEach(image => {
       let src = image.getAttribute('src')
       image.addEventListener('click', function () {
           window.open(src)
       });
    });
  })
</script>
@endsection
