<?php
use Illuminate\Support\Facades\DB;

// if(Auth::user()->role_id == 5){
// Auth::user()->role_id == 5 = DB::table('pegawai')->where("user_id", Auth::user()->id)->where("is_tata_usaha","Y")->first();
// }
// // return Auth::user()->role_id == 5;
// else if(Auth::user()->role_id == 2){
//   $siswa = "tes";

// }

// if(Auth::user()->role_id == 5){

// }
$pegawai = DB::table('pegawai')->where("user_id", Auth::user()->id)->first();

$guru = DB::table('guru')->where("user_id", Auth::user()->id)->first();
$walimurid = DB::table('wali_murid')->where("user_id", Auth::user()->id)->first();

$notifications = DB::table('notifikasi')->where('user_id', Auth::user()->id)->get();
if(!$notifications){
  $notifications = [];
}
?>
<!-- partial:partials/_navbar.html -->
<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
  <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
    <a class="navbar-brand brand-logo" href="{{url('/admin/home')}}">
      <!-- {{ Auth::user()->role_id }} -->
      <img src="{{asset('assets/sipenahorz.png')}}" alt="logo" style="margin-left: auto;">
      <!-- <h1 style="margin:auto; ">iWak</h1> -->
    </a>
    <a class="navbar-brand brand-logo-mini" href="{{url('/admin/home')}}">
      {{-- <img src="{{asset('assets/atonergi-mini.png')}}" alt="logo" /> --}}
      <h1 style="margin:auto; ">{{getsingkatan("iWak")}}</h1>
    </a>
  </div>
  <div class="navbar-menu-wrapper d-flex align-items-stretch">
    <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
      <span class="mdi mdi-menu"></span>
    </button>
    <div class="search-field ml-4 d-none d-md-block">
      <form class="d-flex align-items-stretch h-100" action="#">
        <div class="input-group">
          <input id="filterInput" type="text" class="form-control bg-transparent border-0" placeholder="Search Menu">
          <div class="input-group-btn">
            <button id="btn-reset" type="button" class="btn bg-transparent px-0 d-none" style="cursor: pointer;"><i
                class="fa fa-times"></i></button>
            <!-- <button type="button" class="btn bg-transparent dropdown-toggle px-0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="mdi mdi-earth"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                  <a class="dropdown-item" href="#">Today</a>
                  <a class="dropdown-item" href="#">This week</a>
                  <a class="dropdown-item" href="#">This month</a>
                  <div role="separator" class="dropdown-divider"></div>
                  <a class="dropdown-item" href="#">Month and older</a>
                </div> -->
          </div>
          <div class="input-group-addon bg-transparent border-0 search-button">
            <button type="button" class="btn btn-sm bg-transparent px-0" id="btn-search-menu">
              <i class="mdi mdi-magnify"></i>
            </button>
          </div>
        </div>
      </form>
    </div>
    <ul class="navbar-nav navbar-nav-right">
      <li class="nav-item d-none d-lg-block full-screen-link">
        <a class="nav-link">
          <i class="mdi mdi-fullscreen" id="fullscreen-button"></i>
        </a>
      </li>


      <li class="nav-item dropdown">
        <a class="nav-link nav-profile mdi mdi-bell"" id="profileDropdown" href="#" data-toggle="dropdown"
          aria-expanded="false">
        </a>
        <div class="dropdown-menu navbar-dropdown w-100 p-3 notifikasiStyle" aria-labelledby="profileDropdown">
         @foreach ($notifications as $notification)
          <h5>{{$notification->judul}}</h5>
          <p>{{$notification->deskripsi}}</p>
          @endforeach
        </div>
      </li>
      <!-- @if(Auth::user()->role == 'admin') -->
      <li class="nav-item nav-logout d-none d-lg-block" title="Logout">
        <a class="nav-link" href="{{ url('logout') }}">
          <i class="mdi mdi-power"></i>
        </a>
      </li>
      <!-- @else -->
      <li class="nav-item nav-logout d-none d-lg-block" title="Logout">
        <a class="nav-link" href="{{ url('logout') }}">
          <i class="mdi mdi-power"></i>
        </a>
      </li>
      <!-- @endif -->
      <form id="logout-form" action="{{ url('admin/logout') }}" method="post" style="display: none;">
        {{ csrf_field() }}
      </form>
    </ul>
    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
      data-toggle="offcanvas">
      <span class="mdi mdi-menu"></span>
    </button>
  </div>
</nav>

<!-- partial -->
<div class="container-fluid page-body-wrapper">
  <div class="row row-offcanvas row-offcanvas-right">
    <!-- partial:partials/_sidebar.html -->
    <nav class="sidebar sidebar-offcanvas" id="sidebar">
      <ul class="nav" id="ayaysir">

        <li class="nav-item {{Request::is('admin/home') ? 'active' : ''}} {{Request::is('/') ? 'active' : ''}} ">
          <a class="nav-link" href="{{url('admin/home')}}">
            <span class="menu-title">Dashboard</span>
            {{-- <span class="menu-sub-title">( 2 new updates )</span> --}}
            <i class="mdi mdi-home menu-icon"></i>
          </a>
        </li>
        @if(Auth::user()->role_id == 1 || Auth::user()->role_id == 5 || Auth::user()->role_id == 7)
        @if(DB::table("pegawai")->where("user_id",Auth::user()->id)->where("is_tata_usaha","N")->get()->isEmpty())

        <li class="nav-item {{( ( Request::is('admin/guru/*') || Request::is('admin/guru') ||  Request::is('admin/dinas-pendidikan/*') || Request::is('admin/dinas-pendidikan') ||  Request::is('admin/kepala-sekolah/*') || Request::is('admin/kepala-sekolah') || Request::is('admin/siswa/*') || Request::is('admin/siswa') || Request::is('admin/wali-murid') || Request::is('admin/wali-murid/*') || Request::is('admin/pegawai') || Request::is('admin/pegawai/*') )  ? 'active' : '') }}">
          <a class="nav-link" data-toggle="collapse" href="#dataMaster" aria-expanded="false" aria-controls="ui-basic">
            <span class="menu-title">Data Master</span>
            <i class="menu-arrow"></i>
            <i class="mdi mdi-database menu-icon"></i>
          </a>

          <div class="collapse {{( ( Request::is('admin/guru/*') || Request::is('admin/guru') ||  Request::is('admin/dinas-pendidikan/*') || Request::is('admin/dinas-pendidikan') ||  Request::is('admin/kepala-sekolah/*') || Request::is('admin/kepala-sekolah') || Request::is('admin/siswa/*') || Request::is('admin/siswa') || Request::is('admin/wali-murid') || Request::is('admin/wali-murid/*') || Request::is('admin/pegawai') || Request::is('admin/pegawai/*') )  ? 'show' : '') }}" id="dataMaster">
            <ul class="nav flex-column sub-menu">
              <li class="nav-item"> <a
                  class="nav-link {{Request::is('admin/guru') || Request::is('admin/guru/*') ? 'active' : '' }}"
                  href="{{url('admin/guru')}}">Data Guru<span class="d-none">Setting</span></a></li>
              <li class="nav-item"> <a
                  class="nav-link {{Request::is('admin/siswa') || Request::is('admin/siswa/*') ? 'active' : '' }}"
                  href="{{url('admin/siswa')}}">Data Siswa<span class="d-none">Setting</span></a></li>
              <li class="nav-item"> <a
                  class="nav-link {{Request::is('admin/wali-murid') || Request::is('admin/wali-murid/*') ? 'active' : '' }}"
                  href="{{url('admin/wali-murid')}}">Data Walimurid<span class="d-none">Setting</span></a></li>
              <li class="nav-item"> <a
                  class="nav-link {{Request::is('admin/pegawai') || Request::is('admin/pegawai/*') ? 'active' : '' }}"
                  href="{{url('admin/pegawai')}}">Data Pegawai<span class="d-none">Setting</span></a></li>

                  <li class="nav-item"> <a
                    class="nav-link {{Request::is('admin/dinas-pendidikan') || Request::is('admin/dinas-pendidikan/*') ? 'active' : '' }}"
                    href="{{url('admin/dinas-pendidikan')}}">Dinas Pendidikan<span class="d-none">Setting</span></a></li>
                    <li class="nav-item"> <a
                      class="nav-link {{Request::is('admin/kepala-sekolah') || Request::is('admin/kepala-sekolah/*') ? 'active' : '' }}"
                      href="{{url('admin/kepala-sekolah')}}">Kepala Sekolah<span class="d-none">Setting</span></a></li>
            </ul>
          </div>
        </li>

        @endif
        @endif

        @if(Auth::user()->role_id == 3 || Auth::user()->role_id == 2 || Auth::user()->role_id == 1 || Auth::user()->role_id == 5 || Auth::user()->role_id == 7 || Auth::user()->role_id == 6 || Auth::user()->role_id == 4 )
          <li class="nav-item {{ ( ( Request::is('admin/berita-kelas/*') || Request::is('admin/berita-kelas') || Request::is('admin/berita-sekolah/*') || Request::is('admin/berita-sekolah') ) ? ' active' : '' ) }}">
            <a class="nav-link" data-toggle="collapse" href="#berita" aria-expanded="false" aria-controls="ui-basic">
              <span class="menu-title">Berita</span>
              <i class="menu-arrow"></i>
              <i class="mdi mdi-database menu-icon"></i>
            </a>
            <div class="collapse {{( ( Request::is('admin/berita-kelas/*') || Request::is('admin/berita-kelas') || Request::is('admin/berita-sekolah/*') || Request::is('admin/berita-sekolah') ) ? ' show' : '' ) }}" id="berita">
              <ul class="nav flex-column sub-menu">
        @if(DB::table("pegawai")->where("user_id",Auth::user()->id)->where("is_kantin","Y")->get()->isEmpty() && DB::table("pegawai")->where("user_id",Auth::user()->id)->where("is_koperasi","Y")->get()->isEmpty() && DB::table("pegawai")->where("user_id",Auth::user()->id)->where("is_perpus","Y")->get()->isEmpty() && Auth::user()->role_id != 7 && Auth::user()->role_id != 3 && Auth::user()->role_id != 6 && DB::table("pegawai")->where("user_id",Auth::user()->id)->where("is_pengawas_sekolah","Y")->get()->isEmpty() )

                <li class="nav-item"> <a class="nav-link {{Request::is('admin/berita-kelas/*') || Request::is('admin/berita-kelas') ? 'active' : '' }}" href="{{url('admin/berita-kelas')}}">Berita Kelas<span class="d-none">Berita Kelas</span></a></li>
                @endif
                <li class="nav-item"> <a class="nav-link {{Request::is('admin/berita-sekolah/*') || Request::is('admin/berita-sekolah') ? 'active' : '' }}" href="{{url('admin/berita-sekolah')}}">Berita Sekolah<span class="d-none">Berita Sekolah</span></a></li>
              </ul>
            </div>
          </li>
        @endif

        @if( Auth::user()->role_id == 1 || Auth::user()->role_id == 5 || Auth::user()->role_id == 6 || Auth::user()->role_id == 4 ||  Auth::user()->role_id == 2 )
        @if(DB::table("pegawai")->where("user_id",Auth::user()->id)->where("is_kantin","Y")->get()->isEmpty() && DB::table("pegawai")->where("user_id",Auth::user()->id)->where("is_koperasi","Y")->get()->isEmpty() && DB::table("pegawai")->where("user_id",Auth::user()->id)->where("is_perpus","Y")->get()->isEmpty() && DB::table("pegawai")->where("user_id",Auth::user()->id)->where("is_pengawas_sekolah","Y")->get()->isEmpty())

        <li
          class="nav-item {{ ( ( Request::is('admin/kelas/*') || Request::is('admin/kelas') ) ? ' active' : '' ) }}">
          <a class="nav-link" data-toggle="collapse" href="#kelas" aria-expanded="false" aria-controls="ui-basic">
            <span class="menu-title">Kelas</span>
            <span class="d-none">
              Edit Info
              Manage Info
            </span>
            <i class="menu-arrow"></i>
            <i class="mdi mdi-database menu-icon"></i>
          </a>
          <div
            class="collapse {{(( Request::is('admin/kelas/*') || Request::is('admin/kelas') || Request::is('admin/mata-pelajaran/*') || Request::is('admin/mata-pelajaran') )  ? 'show' : '') }}"
            id="kelas">
            <ul class="nav flex-column sub-menu">

              <li class="nav-item"> <a
                  class="nav-link {{Request::is('admin/kelas') || Request::is('admin/kelas/*') ? 'active' : '' }}"
                  href="{{url('admin/kelas')}}">Data Kelas<span class="d-none">Setting</span></a>
                </li>
                <li class="nav-item"> <a
                  class="nav-link {{Request::is('admin/mata-pelajaran') || Request::is('admin/mata-pelajaran/*') ? 'active' : '' }}"
                  href="{{url('admin/mata-pelajaran')}}">Data Mata Pelajaran<span class="d-none">Setting</span></a>
                </li>
              <li class="nav-item"> <a
                  class="nav-link {{Request::is('admin/jadwal-pembelajaran') || Request::is('admin/jadwal-pembelajaran/*') ? 'active' : '' }}"
                  href="{{url('admin/jadwal-pembelajaran')}}">Jadwal Kelas<span class="d-none">Setting</span></a></li>

            </ul>
          </div>
        </li>
        @endif
        @endif


        @if(Auth::user()->role_id == 3 || Auth::user()->role_id == 1 || Auth::user()->role_id == 5 || Auth::user()->role_id == 7 || Auth::user()->role_id == 6 || Auth::user()->role_id == 4)
        @if(DB::table("pegawai")->where("user_id",Auth::user()->id)->where("is_kantin","Y")->get()->isEmpty() && DB::table("pegawai")->where("user_id",Auth::user()->id)->where("is_koperasi","Y")->get()->isEmpty() && DB::table("pegawai")->where("user_id",Auth::user()->id)->where("is_perpus","Y")->get()->isEmpty())

        <li class="nav-item {{Request::is('admin/mutasisiswa') ? 'active' : ''}}">
          <a class="nav-link" href="{{url('admin/mutasisiswa')}}">
            <span class="menu-title">Mutasi Siswa</span>
            {{-- <span class="menu-sub-title">( 2 new updates )</span> --}}
            <i class="mdi mdi-account-outline menu-icon"></i>
          </a>
        </li>
        @endif
        @endif

        @if(Auth::user()->role_id == 2 || Auth::user()->role_id == 1 || Auth::user()->role_id == 6 || Auth::user()->role_id == 4 || Auth::user()->role_id == 5 && DB::table("pegawai")->where("user_id",Auth::user()->id)->where("is_pengawas_sekolah","Y")->get()->isEmpty())

        <li class="nav-item {{ ( ( Request::is('admin/dompetdigital/*') || Request::is('admin/dompetdigital') || Request::is('admin/approvedompetdigital/*') || Request::is('admin/approvedompetdigital') ) ? ' active' : '' ) }}">
          <a class="nav-link" data-toggle="collapse" href="#dompet-digital" aria-expanded="false" aria-controls="ui-basic">
            <span class="menu-title">Dompet Digital</span>
            <i class="menu-arrow"></i>
            <i class="mdi mdi-database menu-icon"></i>
          </a>
          <div class="collapse {{( ( Request::is('admin/dompetdigital/*') || Request::is('admin/dompetdigital') || Request::is('admin/approvedompetdigital/*') || Request::is('admin/approvedompetdigital')  ? ' show' : '' || Request::is('admin/dompetdigitalsaya/*') || Request::is('admin/dompetdigitalsaya') ) ? ' show' : '' ) }}" id="dompet-digital">
            <ul class="nav flex-column sub-menu">
              <li class="nav-item"> <a class="nav-link {{Request::is('admin/dompetdigitalsaya/*') || Request::is('admin/dompetdigitalsaya') ? 'active' : '' }}" href="{{url('admin/dompetdigitalsaya')}}">Dompet Digital Saya<span class="d-none">Dompet Digital Saya</span></a></li>
              @if(Auth::user()->role_id == 1)

              <li class="nav-item"> <a class="nav-link {{Request::is('admin/dompetdigital/*') || Request::is('admin/dompetdigital') ? 'active' : '' }}" href="{{url('admin/dompetdigital')}}">List Dompet Digital<span class="d-none">List Dompet Digital</span></a></li>
              <li class="nav-item"> <a class="nav-link {{Request::is('admin/approvedompetdigital/*') || Request::is('admin/approvedompetdigital') ? 'active' : '' }}" href="{{url('admin/approvedompetdigital')}}">Permintaan Top Up<span class="d-none">Permintaan Top Up</span></a></li>
              @endif
            </ul>
          </div>
        </li>
        @endif

        @if(Auth::user()->role_id == 2 )
        <li class="nav-item {{Request::is('admin/kartudigitalsaya') ? 'active' : ''}}">
          <a class="nav-link" href="{{url('admin/kartudigitalsaya')}}">
            <span class="menu-title">Kartu Digital Saya</span>
            {{-- <span class="menu-sub-title">( 2 new updates )</span> --}}
            <i class="mdi mdi-wallet menu-icon"></i>
          </a>
        </li>
        @endif

        @if(Auth::user()->role_id == 3 )
        <li class="nav-item {{Request::is('admin/kartudigitalsaya') ? 'active' : ''}}">
          <a class="nav-link" href="{{url('admin/kartudigitalsaya')}}">
            <span class="menu-title">Kartu Digital Siswa Saya</span>
            {{-- <span class="menu-sub-title">( 2 new updates )</span> --}}
            <i class="mdi mdi-wallet menu-icon"></i>
          </a>
        </li>
        @endif
        @if(Auth::user()->role_id == 1 || Auth::user()->role_id == 5 || Auth::user()->role_id == 7 || Auth::user()->role_id == 4 )
        @if(DB::table("pegawai")->where("user_id",Auth::user()->id)->where("is_kantin","Y")->get()->isEmpty() && DB::table("pegawai")->where("user_id",Auth::user()->id)->where("is_koperasi","Y")->get()->isEmpty()&& DB::table("pegawai")->where("user_id",Auth::user()->id)->where("is_perpus","Y")->get()->isEmpty() && DB::table("pegawai")->where("user_id",Auth::user()->id)->where("is_pengawas_sekolah","Y")->get()->isEmpty())

         <li class="nav-item {{Request::is('admin/kartudigital') ? 'active' : ''}}">
          <a class="nav-link" href="{{url('admin/kartudigital')}}">
            <span class="menu-title">Kartu Digital</span>
            {{-- <span class="menu-sub-title">( 2 new updates )</span> --}}
            <i class="mdi mdi-wallet menu-icon"></i>
          </a>
        </li>
        @endif
        @endif


        @if(Auth::user()->role_id == 3 || Auth::user()->role_id == 2 || Auth::user()->role_id == 1 || Auth::user()->role_id == 4 || Auth::user()->role_id == 5 || Auth::user()->role_id == 6)
        @if(DB::table("pegawai")->where("user_id",Auth::user()->id)->where("is_kantin","Y")->get()->isEmpty())

        <li class="nav-item {{ ( ( Request::is('admin/absensisiswa/*') || Request::is('admin/absensisiswa') || Request::is('admin/absensiguru/*') || Request::is('admin/absensiguru') || Request::is('admin/absensipegawai/*') || Request::is('admin/absensipegawai') || Request::is('admin/absensikepalasekolah/*') || Request::is('admin/absensikepalasekolah')  || Request::is('admin/absensipegawaisaya/*') || Request::is('admin/absensipegawaisaya')  || Request::is('admin/absensisiswasaya/*') || Request::is('admin/absensisiswasaya') ? ' active' : '' )) }}">
          <a class="nav-link" data-toggle="collapse" href="#absensi" aria-expanded="false" aria-controls="ui-basic">
            <span class="menu-title">Absensi</span>
            <i class="menu-arrow"></i>
            <i class="mdi mdi-database menu-icon"></i>
          </a>
          <div class="collapse {{((Request::is('admin/dompetdigital/*') || Request::is('admin/dompetdigital') || Request::is('admin/approvedompetdigital/*') || Request::is('admin/approvedompetdigital')  || Request::is('admin/absensigurusaya/*') || Request::is('admin/absensigurusaya')  || Request::is('admin/absensikepalasekolahsaya/*') || Request::is('admin/absensikepalasekolahsaya') ? ' show' : '' ))}}" id="absensi">
          <ul class="nav flex-column sub-menu">
        @if(Auth::user()->role_id == 2 || Auth::user()->role_id == 3 )

              <li class="nav-item"> <a class="nav-link {{Request::is('admin/absensisiswasaya/*') || Request::is('admin/absensisiswasaya') ? 'active' : '' }}" href="{{url('admin/absensisiswasaya')}}">Absensi Saya (Siswa)<span class="d-none">Absensi Saya (Siswa)</span></a></li>
              @endif

        @if(Auth::user()->role_id == 5 && DB::table("pegawai")->where("user_id",Auth::user()->id)->where("is_pengawas_sekolah","Y")->get()->isEmpty() )

              <li class="nav-item"> <a class="nav-link {{Request::is('admin/absensipegawaisaya/*') || Request::is('admin/absensipegawaisaya') ? 'active' : '' }}" href="{{url('admin/absensipegawaisaya')}}">Absensi Saya (Pegawai)<span class="d-none">Absensi Saya (Pegawai)</span></a></li>
              @endif

        @if(Auth::user()->role_id == 6  )

              <li class="nav-item"> <a class="nav-link {{Request::is('admin/absensikepalasekolahsaya/*') || Request::is('admin/absensikepalasekolahsaya') ? 'active' : '' }}" href="{{url('admin/absensikepalasekolahsaya')}}">Absensi Saya (Kepala Sekolah)<span class="d-none">Absensi Saya (Kepala Sekolah)</span></a></li>
              @endif

        @if(Auth::user()->role_id == 4  )

              <li class="nav-item"> <a class="nav-link {{Request::is('admin/absensigurusaya/*') || Request::is('admin/absensigurusaya') ? 'active' : '' }}" href="{{url('admin/absensigurusaya')}}">Absensi Saya (Guru)<span class="d-none">Absensi Saya (Guru)</span></a></li>
        @endif

        @if( Auth::user()->role_id == 1 ||  Auth::user()->role_id == 4 || Auth::user()->role_id == 5 )
        <li class="nav-item"> <a class="nav-link {{Request::is('admin/absensisiswa/*') || Request::is('admin/absensisiswa') ? 'active' : '' }}" href="{{url('admin/absensisiswa')}}">Absensi Siswa<span class="d-none">Absensi Siswa</span></a></li>
              @elseif(DB::table("pegawai")->where("user_id",Auth::user()->id)->where("is_tata_usaha","Y")->get()->isNotEmpty())
              <li class="nav-item"> <a class="nav-link {{Request::is('admin/absensisiswa/*') || Request::is('admin/absensisiswa') ? 'active' : '' }}" href="{{url('admin/absensisiswa')}}">Absensi Siswa<span class="d-none">Absensi Siswa</span></a></li>
              @elseif(DB::table("pegawai")->where("user_id",Auth::user()->id)->where("is_pengawas_sekolah","Y")->get()->isNotEmpty())
              <li class="nav-item"> <a class="nav-link {{Request::is('admin/absensisiswa/*') || Request::is('admin/absensisiswa') ? 'active' : '' }}" href="{{url('admin/absensisiswa')}}">Absensi Siswa<span class="d-none">Absensi Siswa</span></a></li>
              @endif


              @if( Auth::user()->role_id == 1 ||DB::table("pegawai")->where("user_id",Auth::user()->id)->where("is_tata_usaha","Y")->get()->isNotEmpty() )

              <li class="nav-item"> <a class="nav-link {{Request::is('admin/absensiguru/*') || Request::is('admin/absensiguru') ? 'active' : '' }}" href="{{url('admin/absensiguru')}}">Absensi Guru<span class="d-none">Absensi Guru</span></a></li>
              <li class="nav-item"> <a class="nav-link {{Request::is('admin/absensipegawai/*') || Request::is('admin/absensipegawai') ? 'active' : '' }}" href="{{url('admin/absensipegawai')}}">Absensi Pegawai<span class="d-none">Absensi Pegawai</span></a></li>
              <li class="nav-item"> <a class="nav-link {{Request::is('admin/absensikepalasekolah/*') || Request::is('admin/absensikepalasekolah') ? 'active' : '' }}" href="{{url('admin/absensikepalasekolah')}}">Absensi Kepala Sekolah<span class="d-none">Absensi Kepala Sekolah</span></a></li>
              @elseif( Auth::user()->role_id == 1 ||DB::table("pegawai")->where("user_id",Auth::user()->id)->where("is_pengawas_sekolah","Y")->get()->isNotEmpty() )
<li class="nav-item"> <a class="nav-link {{Request::is('admin/absensiguru/*') || Request::is('admin/absensiguru') ? 'active' : '' }}" href="{{url('admin/absensiguru')}}">Absensi Guru<span class="d-none">Absensi Guru</span></a></li>
<li class="nav-item"> <a class="nav-link {{Request::is('admin/absensipegawai/*') || Request::is('admin/absensipegawai') ? 'active' : '' }}" href="{{url('admin/absensipegawai')}}">Absensi Pegawai<span class="d-none">Absensi Pegawai</span></a></li>
<li class="nav-item"> <a class="nav-link {{Request::is('admin/absensikepalasekolah/*') || Request::is('admin/absensikepalasekolah') ? 'active' : '' }}" href="{{url('admin/absensikepalasekolah')}}">Absensi Kepala Sekolah<span class="d-none">Absensi Kepala Sekolah</span></a></li>
              @endif
            </ul>
          </div>
        </li>
        @endif
        @endif


        @if(Auth::user()->role_id == 1 || Auth::user()->role_id == 2 || Auth::user()->role_id == 4 || Auth::user()->role_id == 6 || Auth::user()->role_id == 7 || DB::table('pegawai')->where("user_id",Auth::user()->id)->where("is_perpus","Y")->get()->isNotEmpty() || DB::table('pegawai')->where("user_id",Auth::user()->id)->where("is_pengawas_sekolah","Y")->get()->isNotEmpty() || DB::table('pegawai')->where("user_id",Auth::user()->id)->where("is_tata_usaha","Y")->get()->isNotEmpty() || DB::table('guru')->where("user_id",Auth::user()->id)->get()->isNotEmpty())
        <li class="nav-item {{ ( ( Request::is('admin/katalog-buku/*') || Request::is('admin/katalog-buku') || Request::is('admin/kategori-buku/*') || Request::is('admin/kategori-buku') || Request::is('admin/pinjam-buku/*') || Request::is('admin/pinjam-buku') || Request::is('admin/kembali-buku/*') || Request::is('admin/kembali-buku') || Request::is('admin/sumbang-buku/*') || Request::is('admin/sumbang-buku') || Request::is('admin/kehilangan-buku/*') || Request::is('admin/kehilangan-buku')) ? ' active' : '' ) }}">
          <a class="nav-link" data-toggle="collapse" href="#perpustakaan" aria-expanded="false" aria-controls="ui-basic">
            <span class="menu-title">Perpustakaan</span>
            <i class="menu-arrow"></i>
            <i class="mdi mdi-library menu-icon"></i>
          </a>
          <div class="collapse {{( ( Request::is('admin/katalog-buku/*') || Request::is('admin/katalog-buku') || Request::is('admin/kategori-buku/*') || Request::is('admin/kategori-buku') || Request::is('admin/pinjam-buku/*') || Request::is('admin/pinjam-buku') || Request::is('admin/kembali-buku/*') || Request::is('admin/kembali-buku') || Request::is('admin/sumbang-buku/*') || Request::is('admin/sumbang-buku') || Request::is('admin/kehilangan-buku/*') || Request::is('admin/kehilangan-buku')) ? ' show' : '' ) }}" id="perpustakaan">
            <ul class="nav flex-column sub-menu">
            @if(Auth::user()->role_id != 7  )
            @if(DB::table('pegawai')->where("user_id",Auth::user()->id)->where("is_pengawas_sekolah","Y")->get()->isEmpty())
              <li class="nav-item"> <a class="nav-link {{Request::is('admin/katalog-buku/*') || Request::is('admin/katalog-buku') || Request::is('admin/kategori-buku/*') || Request::is('admin/kategori-buku') ? 'active' : '' }}" href="{{url('admin/katalog-buku')}}">Katalog Buku<span class="d-none">Katalog Buku</span></a></li>
              <li class="nav-item"> <a class="nav-link {{Request::is('admin/pinjam-buku/*') || Request::is('admin/pinjam-buku') ? 'active' : '' }}" href="{{url('admin/pinjam-buku')}}">Pinjam Buku<span class="d-none">Pinjam Buku</span></a></li>
              <li class="nav-item"> <a class="nav-link {{Request::is('admin/sumbang-buku/*') || Request::is('admin/sumbang-buku') ? 'active' : '' }}" href="{{url('admin/sumbang-buku')}}">Sumbang Buku<span class="d-none">Sumbang Buku</span></a></li>
              <li class="nav-item"> <a class="nav-link {{Request::is('admin/kembali-buku/*') || Request::is('admin/kembali-buku') ? 'active' : '' }}" href="{{url('admin/kembali-buku')}}">Kembalikan Buku<span class="d-none">Kembalikan Buku</span></a></li>

              @endif
              @endif

            @if(Auth::user()->role_id == 7 || Auth::user()->role_id == 6)


              <li class="nav-item"> <a class="nav-link {{Request::is('admin/kembali-buku/*') || Request::is('admin/kembali-buku') ? 'active' : '' }}" href="{{url('admin/kembali-buku')}}">Laporan Perpustakaan<span class="d-none">Laporan Perpustakaan</span></a></li>

              

              @elseif(Auth::user()->role_id == 5)
            @if(DB::table('pegawai')->where("user_id",Auth::user()->id)->where("is_pengawas_sekolah","Y")->get()->isNotEmpty())
            <li class="nav-item"> <a class="nav-link {{Request::is('admin/kembali-buku/*') || Request::is('admin/kembali-buku') ? 'active' : '' }}" href="{{url('admin/kembali-buku')}}">Laporan Perpustakaan<span class="d-none">Laporan Perpustakaan</span></a></li>
            @endif
              @endif

            @if(Auth::user()->role_id != 7 )
            @if(DB::table('pegawai')->where("user_id",Auth::user()->id)->where("is_pengawas_sekolah","Y")->get()->isEmpty())


              <li class="nav-item"> <a class="nav-link {{Request::is('admin/kehilangan-buku/*') || Request::is('admin/kehilangan-buku') ? 'active' : '' }}" href="{{url('admin/kehilangan-buku')}}">Kehilangan Buku<span class="d-none">Kehilangan Buku</span></a></li>
              @endif
              @endif
            </ul>
          </div>
        </li>
        @endif

        @if(Auth::user()->role_id == 2 || Auth::user()->role_id == 1 || Auth::user()->role_id == 4 && DB::table("guru")->where("user_id",Auth::user()->id)->where("is_ekstrakulikuler","Y")->get()->isNotEmpty())

        <li class="nav-item {{( ( Request::is('admin/anggota-osis/*') || Request::is('admin/anggota-osis') || Request::is('admin/kegiatan-osis/*') || Request::is('admin/kegiatan-osis')) ? ' show' : '' ) }}">
          <a class="nav-link" data-toggle="collapse" href="#osis" aria-expanded="false" aria-controls="ui-basic">
            <span class="menu-title">Kelola OSIS</span>
            <i class="menu-arrow"></i>
            <i class="mdi mdi-library menu-icon"></i>
          </a>
          <div class="collapse {{( ( Request::is('admin/anggota-osis/*') || Request::is('admin/anggota-osis') || Request::is('admin/kegiatan-osis/*') || Request::is('admin/kegiatan-osis')) ? ' show' : '' ) }}" id="osis">
            <ul class="nav flex-column sub-menu">
        @if(Auth::user()->role_id == 4 &&  DB::table("guru")->where("user_id",Auth::user()->id)->where("is_ekstrakulikuler","Y")->get()->isNotEmpty() || Auth::user()->role_id == 1)

              <li class="nav-item"> <a class="nav-link {{Request::is('admin/anggota-osis/*') || Request::is('admin/anggota-osis') || Request::is('admin/kategori-buku/*') || Request::is('admin/kategori-buku') ? 'active' : '' }}" href="{{url('admin/anggota-osis')}}">Anggota OSIS<span class="d-none">Anggota OSIS</span></a></li>
              @elseif(Auth::user()->role_id == 2)
              <li class="nav-item"> <a class="nav-link {{Request::is('admin/anggota-osis/*') || Request::is('admin/anggota-osis') || Request::is('admin/kategori-buku/*') || Request::is('admin/kategori-buku') ? 'active' : '' }}" href="{{url('admin/anggota-osis')}}">Anggota OSIS<span class="d-none">Anggota OSIS</span></a></li>

              @endif

              @if( Auth::user()->role_id == 1)


              <li class="nav-item"> <a class="nav-link {{Request::is('admin/kegiatan-osis/*') || Request::is('admin/kegiatan-osis') ? 'active' : '' }}" href="{{url('admin/kegiatan-osis')}}">Kegiatan Osis<span class="d-none">Kegiatan Osis</span></a></li>
              @elseif(Auth::user()->role_id == 2 && DB::table("siswa")->where("user_id",Auth::user()->id)->where("is_osis","Y")->get()->isNotEmpty() ||Auth::user()->role_id == 4 &&  DB::table("guru")->where("user_id",Auth::user()->id)->where("is_ekstrakulikuler","Y")->get()->isNotEmpty() )

              <li class="nav-item"> <a class="nav-link {{Request::is('admin/kegiatan-osis/*') || Request::is('admin/kegiatan-osis') ? 'active' : '' }}" href="{{url('admin/kegiatan-osis')}}">Kegiatan Osis<span class="d-none">Kegiatan Osis</span></a></li>

              @endif

            </ul>
          </div>
        </li>
        @endif

        @if(Auth::user()->role_id == 2 || Auth::user()->role_id == 1 || Auth::user()->role_id == 7 || Auth::user()->role_id == 6 || Auth::user()->role_id == 4)
        <li class="nav-item {{Request::is('admin/ekstrakulikuler') ? 'active' : ''}}">
          <a class="nav-link" href="{{url('admin/ekstrakulikuler')}}">
            <span class="menu-title">Ekstrakulikuler</span>
            {{-- <span class="menu-sub-title">( 2 new updates )</span> --}}
            <i class="mdi mdi-swim menu-icon"></i>
          </a>
        </li>
        @endif

        @if(DB::table("pegawai")->where("user_id",Auth::user()->id)->where("is_pengawas_sekolah","Y")->get()->isNotEmpty())
        <li class="nav-item {{Request::is('admin/ekstrakulikuler') ? 'active' : ''}}">
          <a class="nav-link" href="{{url('admin/ekstrakulikuler')}}">
            <span class="menu-title">Ekstrakulikuler</span>
            {{-- <span class="menu-sub-title">( 2 new updates )</span> --}}
            <i class="mdi mdi-swim menu-icon"></i>
          </a>
        </li>
        
        @endif

        @if(Auth::user()->role_id == 1 || DB::table("pegawai")->where("user_id",Auth::user()->id)->where("is_tata_usaha","Y")->get()->isNotEmpty() || DB::table("siswa")->where("user_id",Auth::user()->id)->where("is_osis","Y")->get()->isNotEmpty() || DB::table("guru")->where("user_id",Auth::user()->id)->where("is_mapel","Y")->get()->isNotEmpty() || DB::table("guru")->where("user_id",Auth::user()->id)->where("is_walikelas","Y")->get()->isNotEmpty() )
        <li class="nav-item {{ ( ( Request::is('admin/pinjam-fasilitas/*') || Request::is('admin/pinjam-fasilitas') || Request::is('admin/pinjam-fasilitas/*') || Request::is('admin/pinjam-fasilitas') ) ? ' active' : '' ) }}">
          <a class="nav-link" data-toggle="collapse" href="#pinjam-fasilitas" aria-expanded="false" aria-controls="ui-basic">
            <span class="menu-title">Peminjaman Fasilitas</span>
            <i class="menu-arrow"></i>
            <i class="mdi mdi-database menu-icon"></i>
          </a>
          <div class="collapse {{( ( Request::is('admin/list-fasilitas/*') || Request::is('admin/list-fasilitas') || Request::is('admin/pinjam-fasilitas/*') || Request::is('admin/pinjam-fasilitas') ) ? ' show' : '' ) }}" id="pinjam-fasilitas">
            <ul class="nav flex-column sub-menu">
              @if(Auth::user()->role_id == 1 || DB::table("pegawai")->where("user_id",Auth::user()->id)->where("is_tata_usaha","Y" )->get()->isNotEmpty())
              <li class="nav-item"> <a class="nav-link {{Request::is('admin/list-fasilitas/*') || Request::is('admin/list-fasilitas') ? 'active' : '' }}" href="{{url('admin/list-fasilitas')}}">List Fasilitas<span class="d-none">List Fasilitas</span></a></li>
              @endif
              <li class="nav-item"> <a class="nav-link {{Request::is('admin/pinjam-fasilitas/*') || Request::is('admin/pinjam-fasilitas') ? 'active' : '' }}" href="{{url('admin/pinjam-fasilitas')}}">Pinjam Fasilitas<span class="d-none">Pinjam Fasilitas</span></a></li>
            </ul>
          </div>
        </li>
        @endif

        @if(Auth::user()->role_id == 2 || Auth::user()->role_id == 1 || Auth::user()->role_id == 7 || Auth::user()->role_id == 3 || Auth::user()->role_id == 4 )
        @if(DB::table("pegawai")->where("user_id",Auth::user()->id)->where("is_kantin","Y")->get()->isEmpty())

        <li class="nav-item {{Request::is('admin/nilai-pembelajaran') ? 'active' : ''}}">
          <a class="nav-link" href="{{url('admin/nilai-pembelajaran')}}">
            <span class="menu-title">Pembelajaran Siswa</span>
            {{-- <span class="menu-sub-title">( 2 new updates )</span> --}}
            <i class="mdi mdi-library-books menu-icon"></i>
          </a>
        </li>
        @endif
        @endif

        @if(Auth::user()->role_id == 2 || Auth::user()->role_id == 1 || Auth::user()->role_id == 5 || Auth::user()->role_id == 6 || Auth::user()->role_id == 4)
        @if(DB::table("pegawai")->where("user_id",Auth::user()->id)->where("is_pengawas_sekolah","Y")->get()->isEmpty())

        <li class="nav-item {{ ( ( Request::is('admin/bayar-kantin/*') || Request::is('admin/bayar-kantin') || Request::is('admin/transaksi-kantin/*')  ||  Request::is('admin/withdraw/*') || Request::is('admin/withdraw') || Request::is('admin/transaksi-kantin') ) ? ' active' : '' ) }}">
          <a class="nav-link" data-toggle="collapse" href="#kantin" aria-expanded="false" aria-controls="ui-basic">
            <span class="menu-title">Kantin</span>
            <i class="menu-arrow"></i>
            <i class="mdi mdi-database menu-icon"></i>
          </a>
          <div class="collapse {{( ( Request::is('admin/bayar-kantin/*') || Request::is('admin/bayar-kantin') ||  Request::is('admin/withdraw/*') || Request::is('admin/withdraw') || Request::is('admin/transaksi-kantin/*') || Request::is('admin/transaksi-kantin') ) ? ' show' : '' ) }}" id="kantin">
            <ul class="nav flex-column sub-menu">
              @if( Auth::user()->role_id == 1)
              @if(DB::table("pegawai")->where("user_id",Auth::user()->id)->where("is_kantin","N")->get()->isEmpty())
              <li class="nav-item"> <a class="nav-link {{Request::is('admin/withdraw/*') || Request::is('admin/withdraw') ? 'active' : '' }}" href="{{url('admin/withdraw')}}">Penarikan Dana<span class="d-none">Penarikan Dana</span></a></li>
              @endif
              @endif

        @if(Auth::user()->role_id == 2 || Auth::user()->role_id == 1 || Auth::user()->role_id == 5 || Auth::user()->role_id == 6 || Auth::user()->role_id == 4 )

              <li class="nav-item"> <a class="nav-link {{Request::is('admin/bayar-kantin/*') || Request::is('admin/bayar-kantin') ? 'active' : '' }}" href="{{url('admin/bayar-kantin')}}">Pembelian<span class="d-none">Pembelian</span></a></li>
              @endif

        @if( Auth::user()->role_id == 1 || Auth::user()->role_id == 5)
        @if(DB::table("pegawai")->where("user_id",Auth::user()->id)->where("is_kantin","N")->get()->isEmpty() && DB::table("pegawai")->where("user_id",Auth::user()->id)->where("is_koperasi","Y")->get()->isEmpty() )

              <li class="nav-item"> <a class="nav-link {{Request::is('admin/transaksi-kantin/*') || Request::is('admin/transaksi-kantin') ? 'active' : '' }}" href="{{url('admin/transaksi-kantin')}}">Transaksi Kantin<span class="d-none">Transaksi Kantin</span></a></li>
              @endif
              @endif
            </ul>
          </div>
        </li>
        @endif
        @endif

        @if(Auth::user()->role_id != 7 && DB::table("pegawai")->where("user_id",Auth::user()->id)->where("is_pengawas_sekolah","Y")->get()->isEmpty() && DB::table("wali_murid")->where("user_id",Auth::user()->id)->get()->isEmpty())
        <li class="nav-item {{ ( ( Request::is('admin/list-koperasi/*') || Request::is('admin/list-koperasi') || Request::is('admin/transaksi-koperasi/*') || Request::is('admin/transaksi-koperasi') ) ? ' active' : '' ) }}">
          <a class="nav-link" data-toggle="collapse" href="#koperasi" aria-expanded="false" aria-controls="ui-basic">
            <span class="menu-title">Koperasi Sekolah</span>
            <i class="menu-arrow"></i>
            <i class="mdi mdi-database menu-icon"></i>
          </a>
          <div class="collapse {{( ( Request::is('admin/list-koperasi/*') || Request::is('admin/list-koperasi') || Request::is('admin/transaksi-koperasi/*') || Request::is('admin/transaksi-koperasi') ) ? ' show' : '' ) }}" id="koperasi">
            <ul class="nav flex-column sub-menu">
              <li class="nav-item"> <a class="nav-link {{Request::is('admin/list-koperasi/*') || Request::is('admin/list-koperasi') ? 'active' : '' }}" href="{{url('admin/list-koperasi')}}">List Barang<span class="d-none">List Barang</span></a></li>
            @if(Auth::user()->role_id == 1 || DB::table("pegawai")->where("user_id",Auth::user()->id)->where("is_koperasi","Y")->get()->isNotEmpty())
              <li class="nav-item"> <a class="nav-link {{Request::is('admin/transaksi-koperasi/*') || Request::is('admin/transaksi-koperasi') ? 'active' : '' }}" href="{{url('admin/transaksi-koperasi')}}">Transaksi Koperasi<span class="d-none">Transaksi Koperasi</span></a></li>
              @endif
            </ul>
          </div>
        </li>
        @endif


        @if(Auth::user()->role_id == 2 || Auth::user()->role_id == 1 || Auth::user()->role_id == 5)
        @if(DB::table("pegawai")->where("user_id",Auth::user()->id)->where("is_kantin","Y")->get()->isEmpty()&& DB::table("pegawai")->where("user_id",Auth::user()->id)->where("is_koperasi","Y")->get()->isEmpty()&& DB::table("pegawai")->where("user_id",Auth::user()->id)->where("is_perpus","Y")->get()->isEmpty() && DB::table("pegawai")->where("user_id",Auth::user()->id)->where("is_pengawas_sekolah","Y")->get()->isEmpty())

        <li class="nav-item {{Request::is('admin/jadwal-sekolah') ? 'active' : ''}}">
          <a class="nav-link" href="{{url('admin/jadwal-sekolah')}}">
            <span class="menu-title">Jadwal Sekolah</span>
            {{-- <span class="menu-sub-title">( 2 new updates )</span> --}}
            <i class="mdi mdi-calendar menu-icon"></i>
          </a>
        </li>
        @endif
        @endif

        @if( Auth::user()->role_id == 1 || Auth::user()->role_id == 5 || Auth::user()->role_id == 7 || Auth::user()->role_id == 6 ||  Auth::user()->role_id == 2 ||  Auth::user()->role_id == 3 )
        @if(DB::table("pegawai")->where("user_id",Auth::user()->id)->where("is_kantin","Y")->get()->isEmpty()&& DB::table("pegawai")->where("user_id",Auth::user()->id)->where("is_koperasi","Y")->get()->isEmpty()&& DB::table("pegawai")->where("user_id",Auth::user()->id)->where("is_perpus","Y")->get()->isEmpty())

        <li class="nav-item {{ ( ( Request::is('admin/keuangan/*') || Request::is('admin/keuangan') || Request::is('admin/keuangan/*') || Request::is('admin/keuangan') ) ? ' active' : '' ) }}">
          <a class="nav-link" data-toggle="collapse" href="#keuangan" aria-expanded="false" aria-controls="ui-basic">
            <span class="menu-title">Keuangan Sekolah</span>
            <i class="menu-arrow"></i>
            <i class="mdi mdi-database menu-icon"></i>
          </a>
          <div class="collapse {{( ( Request::is('admin/data-keuangan/*') || Request::is('admin/data-keuangan') || Request::is('admin/kategori-keuangan/*') || Request::is('admin/kategori-keuangan') ) ? ' show' : '' ) }}" id="keuangan">
            <ul class="nav flex-column sub-menu">
              <li class="nav-item"> <a class="nav-link {{Request::is('admin/data-keuangan/*') || Request::is('admin/data-keuangan') ? 'active' : '' }}" href="{{url('admin/data-keuangan')}}">Data Keuangan<span class="d-none">Data Keuangan</span></a></li>

        @if(DB::table("pegawai")->where("user_id",Auth::user()->id)->where("is_tata_usaha","Y")->get()->isNotEmpty() || Auth::user()->role_id == 1)

              <li class="nav-item"> <a class="nav-link {{Request::is('admin/kategori-keuangan/*') || Request::is('admin/kategori-keuangan') ? 'active' : '' }}" href="{{url('admin/kategori-keuangan')}}">Kategori Keuangan<span class="d-none">Kategori Keuangan</span></a></li>
              @endif
            </ul>
          </div>
        </li>
        @endif
        @endif

        @if(Auth::user()->role_id == 1 || Auth::user()->role_id == 5 || Auth::user()->role_id == 7 || Auth::user()->role_id == 6 || Auth::user()->role_id == 4)
        @if(DB::table("pegawai")->where("user_id",Auth::user()->id)->where("is_kantin","Y")->get()->isEmpty()&& DB::table("pegawai")->where("user_id",Auth::user()->id)->where("is_koperasi","Y")->get()->isEmpty()&& DB::table("pegawai")->where("user_id",Auth::user()->id)->where("is_perpus","Y")->get()->isEmpty())

        <li class="nav-item {{Request::is('admin/ppdb/list') ? 'active' : ''}}">
          <a class="nav-link" href="{{url('admin/ppdb/list')}}">
            <span class="menu-title">PPDB</span>

            <i class="mdi mdi-calendar menu-icon"></i>
          </a>
        </li>
        @endif
        @endif

      </ul>

    </nav>
