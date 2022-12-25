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

@endsection

@section('extra_script')
<script type="text/javascript">

</script>
@endsection
