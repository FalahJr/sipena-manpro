<!-- partial:partials/_navbar.html -->
<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
  <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
    <a class="navbar-brand brand-logo" href="{{url('/admin/home')}}">
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


      @if(Auth::user()->role != 'admin')
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle nav-profile" id="profileDropdown" href="#" data-toggle="dropdown"
          aria-expanded="false">
          {{-- <img src="{{asset('assets/image/faces1.jpg')}}" alt="image"> --}}
          <span class="d-lg-inline">{{Auth::user()->fullname}}</span>
        </a>
        <div class="dropdown-menu navbar-dropdown w-100" aria-labelledby="profileDropdown">

          <a class="dropdown-item" href="{{ url('logout') }}">
            <i class="mdi mdi-logout mr-2 text-primary"></i>
            Home
          </a>

        </div>
      </li>
      @endif
      @if(Auth::user()->role == 'admin')
      <li class="nav-item nav-logout d-none d-lg-block" title="Logout">
        <a class="nav-link" href="{{ url('logout') }}">
          <i class="mdi mdi-power"></i>
        </a>
      </li>
      @else
      <li class="nav-item nav-logout d-none d-lg-block" title="Logout">
        <a class="nav-link" href="{{ url('/') }}">
          <i class="mdi mdi-power"></i>
        </a>
      </li>
      @endif
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

        @if (Auth::user()->role_id == 1)
        <li class="nav-item {{Request::is('admin/home') ? 'active' : ''}} {{Request::is('/') ? 'active' : ''}} ">
          <a class="nav-link" href="{{url('admin/home')}}">
            <span class="menu-title">Dashboard</span>
            {{-- <span class="menu-sub-title">( 2 new updates )</span> --}}
            <i class="mdi mdi-home menu-icon"></i>
          </a>
        </li>
        <li class="nav-item {{( ( Request::is('admin/guru/*') || Request::is('admin/guru') || Request::is('admin/siswa/*') || Request::is('admin/siswa') || Request::is('admin/wali-murid') || Request::is('admin/wali-murid/*') || Request::is('admin/pegawai') || Request::is('admin/pegawai/*') )  ? 'active' : '') }}">
          <a class="nav-link" data-toggle="collapse" href="#dataMaster" aria-expanded="false" aria-controls="ui-basic">
            <span class="menu-title">Data Master</span>
            <i class="menu-arrow"></i>
            <i class="mdi mdi-database menu-icon"></i>
          </a>

          <div class="collapse {{( ( Request::is('admin/guru/*') || Request::is('admin/guru') || Request::is('admin/siswa/*') || Request::is('admin/siswa') || Request::is('admin/wali-murid') || Request::is('admin/wali-murid/*') || Request::is('admin/pegawai') || Request::is('admin/pegawai/*') )  ? 'show' : '') }}" id="dataMaster">
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
            </ul>
          </div>
        </li>

        <li class="nav-item {{ ( ( Request::is('admin/berita-kelas/*') || Request::is('admin/berita-kelas') || Request::is('admin/berita-sekolah/*') || Request::is('admin/berita-sekolah') ) ? ' active' : '' ) }}">
          <a class="nav-link" data-toggle="collapse" href="#berita" aria-expanded="false" aria-controls="ui-basic">
            <span class="menu-title">Berita</span>
            <i class="menu-arrow"></i>
            <i class="mdi mdi-database menu-icon"></i>
          </a>
          <div class="collapse {{( ( Request::is('admin/berita-kelas/*') || Request::is('admin/berita-kelas') || Request::is('admin/berita-sekolah/*') || Request::is('admin/berita-sekolah') ) ? ' show' : '' ) }}" id="berita">
            <ul class="nav flex-column sub-menu">
              <li class="nav-item"> <a class="nav-link {{Request::is('admin/berita-kelas/*') || Request::is('admin/berita-kelas') ? 'active' : '' }}" href="{{url('admin/berita-kelas')}}">Berita Kelas<span class="d-none">Berita Kelas</span></a></li>
              <li class="nav-item"> <a class="nav-link {{Request::is('admin/berita-sekolah/*') || Request::is('admin/berita-sekolah') ? 'active' : '' }}" href="{{url('admin/berita-sekolah')}}">Berita Sekolah<span class="d-none">Berita Sekolah</span></a></li>
            </ul>
          </div>
        </li> 

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

        <li class="nav-item {{Request::is('admin/mutasisiswa') ? 'active' : ''}}">
          <a class="nav-link" href="{{url('admin/mutasisiswa')}}">
            <span class="menu-title">Mutasi Siswa</span>
            {{-- <span class="menu-sub-title">( 2 new updates )</span> --}}
            <i class="mdi mdi-account-outline menu-icon"></i>
          </a>
        </li>

        <li class="nav-item {{Request::is('admin/feed') ? 'active' : ''}}">
          <a class="nav-link" href="{{url('admin/feed')}}">
            <span class="menu-title">Dompet Digital</span>
            {{-- <span class="menu-sub-title">( 2 new updates )</span> --}}
            <i class="mdi mdi-wallet menu-icon"></i>
          </a>
        </li>

        <li class="nav-item {{Request::is('admin/absensisiswa') ? 'active' : ''}}">
          <a class="nav-link" href="{{url('admin/absensisiswa')}}">
            <span class="menu-title">Absensi Siswa</span>
            {{-- <span class="menu-sub-title">( 2 new updates )</span> --}}
            <i class="mdi mdi-note menu-icon"></i>
          </a>
        </li>

        <li class="nav-item {{Request::is('admin/feed') ? 'active' : ''}}">
          <a class="nav-link" href="{{url('admin/feed')}}">
            <span class="menu-title">Absensi Pegawai</span>
            {{-- <span class="menu-sub-title">( 2 new updates )</span> --}}
            <i class="mdi mdi-note menu-icon"></i>
          </a>
        </li>

        <li class="nav-item {{ ( ( Request::is('admin/katalog-buku/*') || Request::is('admin/katalog-buku') || Request::is('admin/kategori-buku/*') || Request::is('admin/kategori-buku') || Request::is('admin/pinjam-buku/*') || Request::is('admin/pinjam-buku') || Request::is('admin/kembali-buku/*') || Request::is('admin/kembali-buku') || Request::is('admin/sumbang-buku/*') || Request::is('admin/sumbang-buku') || Request::is('admin/kehilangan-buku/*') || Request::is('admin/kehilangan-buku')) ? ' active' : '' ) }}">
          <a class="nav-link" data-toggle="collapse" href="#perpustakaan" aria-expanded="false" aria-controls="ui-basic">
            <span class="menu-title">Perpustakaan</span>
            <i class="menu-arrow"></i>
            <i class="mdi mdi-library menu-icon"></i>
          </a>
          <div class="collapse {{( ( Request::is('admin/katalog-buku/*') || Request::is('admin/katalog-buku') || Request::is('admin/kategori-buku/*') || Request::is('admin/kategori-buku') || Request::is('admin/pinjam-buku/*') || Request::is('admin/pinjam-buku') || Request::is('admin/kembali-buku/*') || Request::is('admin/kembali-buku') || Request::is('admin/sumbang-buku/*') || Request::is('admin/sumbang-buku') || Request::is('admin/kehilangan-buku/*') || Request::is('admin/kehilangan-buku')) ? ' show' : '' ) }}" id="perpustakaan">
            <ul class="nav flex-column sub-menu">
              <li class="nav-item"> <a class="nav-link {{Request::is('admin/katalog-buku/*') || Request::is('admin/katalog-buku') || Request::is('admin/kategori-buku/*') || Request::is('admin/kategori-buku') ? 'active' : '' }}" href="{{url('admin/katalog-buku')}}">Katalog Buku<span class="d-none">Katalog Buku</span></a></li>
              <li class="nav-item"> <a class="nav-link {{Request::is('admin/pinjam-buku/*') || Request::is('admin/pinjam-buku') ? 'active' : '' }}" href="{{url('admin/pinjam-buku')}}">Pinjam Buku<span class="d-none">Pinjam Buku</span></a></li>
              <li class="nav-item"> <a class="nav-link {{Request::is('admin/sumbang-buku/*') || Request::is('admin/sumbang-buku') ? 'active' : '' }}" href="{{url('admin/sumbang-buku')}}">Sumbang Buku<span class="d-none">Sumbang Buku</span></a></li>
              <li class="nav-item"> <a class="nav-link {{Request::is('admin/kembali-buku/*') || Request::is('admin/kembali-buku') ? 'active' : '' }}" href="{{url('admin/kembali-buku')}}">Kembalikan Buku<span class="d-none">Kembalikan Buku</span></a></li>
              <li class="nav-item"> <a class="nav-link {{Request::is('admin/kehilangan-buku/*') || Request::is('admin/kehilangan-buku') ? 'active' : '' }}" href="{{url('admin/kehilangan-buku')}}">Kehilangan Buku<span class="d-none">Kehilangan Buku</span></a></li>
            </ul>
          </div>
        </li> 
        <li class="nav-item {{Request::is('admin/kegiatan-osis') ? 'active' : ''}}">
          <a class="nav-link" href="{{url('admin/kegiatan-osis')}}">
            <span class="menu-title">Kegiatan OSIS</span>
            {{-- <span class="menu-sub-title">( 2 new updates )</span> --}}
            <i class="mdi mdi-worker menu-icon"></i>
          </a>
        </li>
        <li class="nav-item {{Request::is('admin/ekstrakulikuler') ? 'active' : ''}}">
          <a class="nav-link" href="{{url('admin/ekstrakulikuler')}}">
            <span class="menu-title">Ekstrakulikuler</span>
            {{-- <span class="menu-sub-title">( 2 new updates )</span> --}}
            <i class="mdi mdi-swim menu-icon"></i>
          </a>
        </li>
        <li class="nav-item {{ ( ( Request::is('admin/pinjam-fasilitas/*') || Request::is('admin/pinjam-fasilitas') || Request::is('admin/berita-sekolah/*') || Request::is('admin/berita-sekolah') ) ? ' active' : '' ) }}">
          <a class="nav-link" data-toggle="collapse" href="#pinjam-fasilitas" aria-expanded="false" aria-controls="ui-basic">
            <span class="menu-title">Peminjaman Fasilitas</span>
            <i class="menu-arrow"></i>
            <i class="mdi mdi-database menu-icon"></i>
          </a>
          <div class="collapse {{( ( Request::is('admin/list-fasilitas/*') || Request::is('admin/list-fasilitas') || Request::is('admin/pinjam-fasilitas/*') || Request::is('admin/pinjam-fasilitas') ) ? ' show' : '' ) }}" id="pinjam-fasilitas">
            <ul class="nav flex-column sub-menu">
              <li class="nav-item"> <a class="nav-link {{Request::is('admin/list-fasilitas/*') || Request::is('admin/list-fasilitas') ? 'active' : '' }}" href="{{url('admin/list-fasilitas')}}">List Fasilitas<span class="d-none">List Fasilitas</span></a></li>
              <li class="nav-item"> <a class="nav-link {{Request::is('admin/pinjam-fasilitas/*') || Request::is('admin/pinjam-fasilitas') ? 'active' : '' }}" href="{{url('admin/pinjam-fasilitas')}}">Pinjam Fasilitas<span class="d-none">Pinjam Fasilitas</span></a></li>
            </ul>
          </div>
        </li> 
        <li class="nav-item {{Request::is('admin/feed') ? 'active' : ''}}">
          <a class="nav-link" href="{{url('admin/feed')}}">
            <span class="menu-title">Pembelajaran Siswa</span>
            {{-- <span class="menu-sub-title">( 2 new updates )</span> --}}
            <i class="mdi mdi-library-books menu-icon"></i>
          </a>
        </li>
        <li class="nav-item {{ ( ( Request::is('admin/bayar-kantin/*') || Request::is('admin/bayar-kantin') || Request::is('admin/transaksi-kantin/*') || Request::is('admin/transaksi-kantin') ) ? ' active' : '' ) }}">
          <a class="nav-link" data-toggle="collapse" href="#kantin" aria-expanded="false" aria-controls="ui-basic">
            <span class="menu-title">Pembayaran Kantin</span>
            <i class="menu-arrow"></i>
            <i class="mdi mdi-database menu-icon"></i>
          </a>
          <div class="collapse {{( ( Request::is('admin/bayar-kantin/*') || Request::is('admin/bayar-kantin') || Request::is('admin/transaksi-kantin/*') || Request::is('admin/transaksi-kantin') ) ? ' show' : '' ) }}" id="kantin">
            <ul class="nav flex-column sub-menu">
              <li class="nav-item"> <a class="nav-link {{Request::is('admin/bayar-kantin/*') || Request::is('admin/bayar-kantin') ? 'active' : '' }}" href="{{url('admin/bayar-kantin')}}">Bayar QRCode<span class="d-none">Bayar QRCode</span></a></li>
              <li class="nav-item"> <a class="nav-link {{Request::is('admin/transaksi-kantin/*') || Request::is('admin/transaksi-kantin') ? 'active' : '' }}" href="{{url('admin/transaksi-kantin')}}">Transaksi Kantin<span class="d-none">Transaksi Kantin</span></a></li>
            </ul>
          </div>
        </li> 
        <li class="nav-item {{Request::is('admin/feed') ? 'active' : ''}}">
          <a class="nav-link" href="{{url('admin/feed')}}">
            <span class="menu-title">Jadwal Kelas</span>
            {{-- <span class="menu-sub-title">( 2 new updates )</span> --}}
            <i class="mdi mdi-calendar menu-icon"></i>
          </a>
        </li>

        @else
        <li class="nav-item {{Request::is('penjual/home') ? 'active' : ''}}">
          <a class="nav-link" href="{{url('penjual/home')}}">
            <span class="menu-title">Store Performance Report</span>
            {{-- <span class="menu-sub-title">( 2 new updates )</span> --}}
            <i class="mdi mdi-chart-areaspline menu-icon"></i>
          </a>
        </li>

        <li class="nav-item {{Request::is('penjual/toko') ? 'active' : ''}}">
          <a class="nav-link" href="{{url('penjual/toko')}}">
            <span class="menu-title">Store</span>
            {{-- <span class="menu-sub-title">( 2 new updates )</span> --}}
            <i class="mdi mdi-store menu-icon"></i>
          </a>
        </li>

        <li class="nav-item {{Request::is('penjual/produk') ? 'active' : ''}}">
          <a class="nav-link" href="{{url('penjual/produk')}}">
            <span class="menu-title">Manage Product</span>
            {{-- <span class="menu-sub-title">( 2 new updates )</span> --}}
            <i class="mdi mdi-format-list-bulleted menu-icon"></i>
          </a>
        </li>

        <li class="nav-item {{Request::is('penjual/lelang') ? 'active' : ''}}">
          <a class="nav-link" href="{{url('penjual/lelang')}}">
            <span class="menu-title">Manage Action</span>
            <span class="menu-sub-title" id="lelangnotif">( 0 new )</span>
            <i class="mdi mdi-sale menu-icon"></i>
          </a>
        </li>

        <li class="nav-item {{Request::is('penjual/listorder') ? 'active' : ''}}">
          <a class="nav-link" href="{{url('penjual/listorder')}}">
            <span class="menu-title">List Order</span>
            <span class="menu-sub-title" id="pesanannotif">( 0 new )</span>
            <i class="mdi mdi-cart-outline menu-icon"></i>
          </a>
        </li>

        <li class="nav-item {{Request::is('penjual/listfeed') ? 'active' : ''}}">
          <a class="nav-link" href="{{url('penjual/listfeed')}}">
            <span class="menu-title">List Feedback / Review</span>
            {{-- <span class="menu-sub-title">( 2 new updates )</span> --}}
            <i class="mdi mdi-comment menu-icon"></i>
          </a>
        </li>

        @endif


        {{-- <li class="nav-item {{Request::is('mutasi') ? 'active' : ''}}">
          <a class="nav-link" href="{{url('/mutasi')}}">
            <span class="menu-title">Mutation Check</span> --}}
            {{-- <span class="menu-sub-title">( 2 new updates )</span> --}}
            {{-- <i class="fa fa-history"></i>
          </a>
        </li> --}}
        {{-- <li
          class="nav-item {{Request::is('setting') ? 'active' : '' || Request::is('setting/*') ? 'active' : '' }}">
          <a class="nav-link" data-toggle="collapse" href="#setting" aria-expanded="false" aria-controls="ui-basic">
            <span class="menu-title">Setup</span>
            <span class="d-none">
              Level Account Setting
              Account Setting
              Permission Setting
              Menu List Setting
            </span>
            <i class="menu-arrow"></i>
            <i class="mdi mdi-settings menu-icon mdi-spin"></i>
          </a>
          <div class="collapse {{Request::is('setting') ? 'show' : '' || Request::is('setting/*') ? 'show' : '' }}"
            id="setting">
            <ul class="nav flex-column sub-menu">
              <li class="nav-item"> <a
                  class="nav-link {{Request::is('setting/modul/keuangan/setting/klasifikasi-akun') ? 'active' : '' || Request::is('setting/modul/keuangan/setting/klasifikasi-akun/*') ? 'active' : '' }}"
                  href="{{url('setting/modul/keuangan/setting/klasifikasi-akun')}}">Klasifikasi Akun<span
                    class="d-none">Setting</span></a></li>
              <li class="nav-item"> <a
                  class="nav-link {{Request::is('setting/modul/keuangan/setting/klasifikasi-akun') ? 'active' : '' || Request::is('setting/modul/keuangan/setting/klasifikasi-akun/*') ? 'active' : '' }}"
                  href="{{url('setting/modul/keuangan/setting/klasifikasi-akun')}}">Klasifikasi Akun<span
                    class="d-none">Setting</span></a></li>

            </ul>
          </div>
        </li> --}}

      </ul>

    </nav>
