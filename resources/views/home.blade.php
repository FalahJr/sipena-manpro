@extends('main')

@section('content')
<!-- partial -->
<div class="content-wrapper">
    <div class="col-lg-12">
      <h1>Selamat Datang {{ Auth::user()->username }} di Sipena</h1>
      @if(DB::table("pegawai")->where("user_id",Auth::user()->id)->where("is_perpus","Y" )->get()->isNotEmpty())
      <div class="card col-4 p-5 text-center">
      <h3>Saldo Perpustakaan</h3>
      <h2> {{FormatRupiahFront(DB::table("perpustakaan")->first()->saldo)}}</h2>
      </div>
      @endif

      @if(DB::table("pegawai")->where("user_id",Auth::user()->id)->where("is_kantin","Y" )->get()->isNotEmpty())
      <div class="card col-4 p-5 text-center">
      <h3>Saldo Kantin</h3>
      <h2> {{FormatRupiahFront(DB::table("kantin")->where("pegawai_id",DB::table("pegawai")->where("user_id",Auth::user()->id)->first()->id)->first()->saldo)}}</h2>
      <button type="button" class="btn btn-info mt-2" onclick="showcreate()">Ajukan Penarikan</button>  
    </div>
      @endif

      @if(DB::table("pegawai")->where("user_id",Auth::user()->id)->where("is_koperasi","Y" )->get()->isNotEmpty())
      <div class="card col-4 p-5 text-center">
      <h3>Saldo Koperasi</h3>
      <h2> {{FormatRupiahFront(DB::table("koperasi")->first()->saldo)}}</h2>
      </div>
      @endif
           <!-- <div class="row">
            <div class="col-md-12 stretch-card grid-margin">
              <div class="card bg-gradient-info text-white">
                <div class="card-body">
                  <h4 class="font-weight-normal mb-3">Jumlah User</h4>
                  <h2 class="font-weight-normal mb-5" id="jumlahuser"></h2>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12 stretch-card grid-margin">
              <div class="card bg-gradient-warning text-white">
                <div class="card-body">
                  <h4 class="font-weight-normal mb-3">Jumlah Toko</h4>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12 stretch-card grid-margin">
              <div class="card bg-gradient-success text-white">
                <div class="card-body">
                  <h4 class="font-weight-normal mb-3">Jumlah User Online</h4>
                  <h2 class="font-weight-normal mb-5" id="jumlahuseronline">  </h2>
                </div>
              </div>
            </div>
          </div> -->
        </div>
    </div>
    @if(DB::table("pegawai")->where("user_id",Auth::user()->id)->where("is_kantin","Y" )->get()->isNotEmpty())
<!-- Modal -->
<div id="tambah" class="modal fade" role="dialog">
  <div class="modal-dialog modal-xs">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-gradient-info">
        <h4 class="modal-title">Form Ajukan Penarikan</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="row">
          <table class="table table_modal">
            <tr>
              <td>Saldo Kantin</td>
              <td>
                <input type="text" readonly class="form-control form-control-sm id" name="saldo" value="{{FormatRupiahFront(DB::table("kantin")->where("pegawai_id",DB::table("pegawai")->where("user_id",Auth::user()->id)->first()->id)->first()->saldo)}}">
              </td>
            </tr>
          <tr>
            <td>Nominal Penarikan <span style="color:red;">*</span></td>
            <td>
              <input type="text" class="form-control form-control-sm inputtext nama" name="nominal">
            </td>
          </tr>
          <tr>
            <td>Keterangan <span style="color:red;">*</span></td>
            <td>
              <input type="text" class="form-control form-control-sm inputtext nama" name="keterangan">
            </td>
          </tr>
          </table>
        </div>
        <div class="modal-footer">
          <button class="btn btn-success" id="simpan" type="button">Simpan</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
        </div>
      </div>
      </div>

  </div>
</div>
@endif
@endsection

@section('extra_script')
<script type="text/javascript">
    function showcreate() {
      $('#tambah').modal('show');
    }
    $('#simpan').click(function () {

var formdata = new FormData();
// formdata.append('image', $('.uploadGambar')[0].files[0]);
// var data = 
$.ajax({
  type: "post",
  url: 'withdraw?_token=' + "{{csrf_token()}}&" + $('.table_modal .inputtext' ).serialize(),
  data: formdata,
  processData: false, //important
  contentType: false,
  cache: false,
  success: function (data) {
    if (data.status == 1) {
      iziToast.success({
        icon: 'fa fa-save',
        message: data.message,
      });
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
</script>
@endsection
