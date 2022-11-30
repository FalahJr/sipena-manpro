<?php

Route::get('/', function () {
    $user = \Auth::user();
    if($user == null) {
      return view('auth.login');
    } else {
      if($user->role_id == 1) {
        return redirect()->route('homeadmin');
      }
    }
}
);

Route::get('loginadmin', 'loginController@authenticate')->name('loginadmin');

//Route untuk user admin
Route::group(['middleware' => 'admin'], function () {

    //Admin Module
    Route::prefix('admin')->group(function () {

        Route::get('/home', 'HomeController@index')->name('homeadmin');

        Route::get('/logout', 'HomeController@logout')->name('logoutadmin');

        //User
        Route::get('/user', 'UserController@index');
        Route::get('/user/table', 'UserController@datatable');
        Route::post('/user/simpan', 'UserController@simpan');
        Route::get('/user/edit', 'UserController@edit');
        Route::get('/user/hapus', 'UserController@hapus');

        //Guru
        Route::get('/guru', 'GuruController@index');
        Route::post('/guru/simpan', 'GuruController@simpan');
        Route::get('/guru/hapus/{id}', 'GuruController@hapus');
        Route::get('/guru/table', 'GuruController@datatable');
        Route::post('/guru/update', 'GuruController@update');
        Route::get('/guru/edit/{id}', 'GuruController@edit');

        //siswa
        Route::get('/siswa', 'SiswaController@index');
        Route::post('/siswa/simpan', 'SiswaController@simpan');
        Route::get('/siswa/hapus/{id}', 'SiswaController@hapus');
        Route::get('/siswa/table', 'SiswaController@datatable');
        Route::post('/siswa/update', 'SiswaController@update');
        Route::get('/siswa/edit/{id}', 'SiswaController@edit');

        //Pegawai
        Route::get('/pegawai', 'PegawaiController@index');
        Route::post('/pegawai/simpan', 'PegawaiController@simpan');
        Route::get('/pegawai/hapus/{id}', 'PegawaiController@hapus');
        Route::get('/pegawai/table', 'PegawaiController@datatable');
        Route::post('/pegawai/update', 'PegawaiController@update');
        Route::get('/pegawai/edit/{id}', 'PegawaiController@edit');

        //wali murid
        Route::get('/wali-murid', 'WaliMuridController@index');
        Route::post('/wali-murid/simpan', 'WaliMuridController@simpan');
        Route::get('/wali-murid/hapus/{id}', 'WaliMuridController@hapus');
        Route::get('/wali-murid/table', 'WaliMuridController@datatable');
        Route::post('/wali-murid/update', 'WaliMuridController@update');
        Route::get('/wali-murid/edit/{id}', 'WaliMuridController@edit');

        //kelas
        Route::get('/kelas', 'KelasController@index');
        Route::post('/kelas/simpan', 'KelasController@simpan');
        Route::get('/kelas/hapus/{id}', 'KelasController@hapus');
        Route::get('/kelas/table', 'KelasController@datatable');
        Route::post('/kelas/update', 'KelasController@update');
        Route::get('/kelas/edit/{id}', 'KelasController@edit');

        //mata pelajaran
        Route::get('/mata-pelajaran', 'MataPelajaranController@index');
        Route::post('/mata-pelajaran/simpan', 'MataPelajaranController@simpan');
        Route::get('/mata-pelajaran/hapus/{id}', 'MataPelajaranController@hapus');
        Route::get('/mata-pelajaran/table', 'MataPelajaranController@datatable');
        Route::post('/mata-pelajaran/update', 'MataPelajaranController@update');
        Route::get('/mata-pelajaran/edit/{id}', 'MataPelajaranController@edit');

        //Jadwal Kelas
        Route::get('/jadwal-pembelajaran', 'JadwalPembelajaranController@index');
        Route::post('/jadwal-pembelajaran/simpan', 'JadwalPembelajaranController@simpan');
        Route::get('/jadwal-pembelajaran/hapus/{id}', 'JadwalPembelajaranController@hapus');
        Route::get('/jadwal-pembelajaran/table', 'JadwalPembelajaranController@datatable');
        Route::post('/jadwal-pembelajaran/update', 'JadwalPembelajaranController@update');
        Route::get('/jadwal-pembelajaran/edit/{id}', 'JadwalPembelajaranController@edit');

        //Berita Sekolah
        Route::get('/berita-sekolah', 'BeritaSekolahController@index');
        Route::post('/berita-sekolah/simpan', 'BeritaSekolahController@simpan');
        Route::get('/berita-sekolah/hapus/{id}', 'BeritaSekolahController@hapus');
        Route::get('/berita-sekolah/table', 'BeritaSekolahController@datatable');
        Route::post('/berita-sekolah/update', 'BeritaSekolahController@update');
        Route::get('/berita-sekolah/edit/{id}', 'BeritaSekolahController@edit');

        //Berita Kelas
        Route::get('/berita-kelas', 'BeritaKelasController@index');
        Route::post('/berita-kelas/simpan', 'BeritaKelasController@simpan');
        Route::get('/berita-kelas/hapus/{id}', 'BeritaKelasController@hapus');
        Route::get('/berita-kelas/table', 'BeritaKelasController@datatable');
        Route::post('/berita-kelas/update', 'BeritaKelasController@update');
        Route::get('/berita-kelas/edit/{id}', 'BeritaKelasController@edit');

        //Bayar QRCode
        Route::get('/bayar-kantin', 'BayarKantinController@index');
        Route::get('/bayar-kantin/table', 'BayarKantinController@datatable');
        Route::get('/bayar-kantin/{id}', 'BayarKantinController@toBayar');
        Route::post('/bayar-kantin/bayar', 'BayarKantinController@bayar');
        Route::post('/bayar-kantin/simpan', 'BayarKantinController@simpan');
        Route::get('/bayar-kantin/hapus/{id}', 'BayarKantinController@hapus');
        Route::post('/bayar-kantin/update', 'BayarKantinController@update');
        Route::get('/bayar-kantin/edit/{id}', 'BayarKantinController@edit');

        //Transaksi Kantin
        Route::get('/transaksi-kantin', 'TransaksiKantinController@index');
        Route::get('/transaksi-kantin/hapus/{id}', 'TransaksiKantinController@hapus');
        Route::get('/transaksi-kantin/table', 'TransaksiKantinController@datatable');
        Route::post('/transaksi-kantin/update', 'TransaksiKantinController@update');
        Route::get('/transaksi-kantin/edit/{id}', 'TransaksiKantinController@edit');

        //Perpustakaan
        Route::get('/katalog-buku', 'Perpustakaan\KatalogBukuController@index');
        Route::get('/katalog-buku/table', 'Perpustakaan\KatalogBukuController@datatable');
        Route::post('/katalog-buku/simpan', 'Perpustakaan\KatalogBukuController@simpan');
        Route::get('/katalog-buku/hapus/{id}', 'Perpustakaan\KatalogBukuController@hapus');
        Route::post('/katalog-buku/update', 'Perpustakaan\KatalogBukuController@update');
        Route::get('/katalog-buku/edit/{id}', 'Perpustakaan\KatalogBukuController@edit');

        Route::get('/kategori-buku', 'Perpustakaan\KategoriBukuController@index');
        Route::get('/kategori-buku/table', 'Perpustakaan\KategoriBukuController@datatable');
        Route::post('/kategori-buku/simpan', 'Perpustakaan\KategoriBukuController@simpan');
        Route::get('/kategori-buku/hapus/{id}', 'Perpustakaan\KategoriBukuController@hapus');
        Route::post('/kategori-buku/update', 'Perpustakaan\KategoriBukuController@update');
        Route::get('/kategori-buku/edit/{id}', 'Perpustakaan\KategoriBukuController@edit');

        Route::get('/pinjam-buku', 'Perpustakaan\PinjamBukuController@index');
        Route::get('/pinjam-buku/table', 'Perpustakaan\PinjamBukuController@datatable');
        Route::post('/pinjam-buku/update', 'Perpustakaan\PinjamBukuController@update');
        Route::get('/pinjam-buku/show/{id}', 'Perpustakaan\PinjamBukuController@show');
        // Route::post('/pinjam-buku/simpan', 'Perpustakaan\PinjamBukuController@simpan');
        Route::get('/pinjam-buku/hapus/{id}', 'Perpustakaan\PinjamBukuController@hapus');
        Route::get('/pinjam-buku/edit/{id}', 'Perpustakaan\PinjamBukuController@edit');

        Route::get('/kembali-buku', 'Perpustakaan\KembaliBukuController@index');
        Route::get('/kembali-buku/table', 'Perpustakaan\KembaliBukuController@datatable');
        Route::post('/kembali-buku/update', 'Perpustakaan\KembaliBukuController@update');
        Route::get('/kembali-buku/show/{id}', 'Perpustakaan\KembaliBukuController@show');
        // Route::post('/kembali-buku/simpan', 'Perpustakaan\KembaliBukuController@simpan');
        Route::get('/kembali-buku/hapus/{id}', 'Perpustakaan\KembaliBukuController@hapus');
        Route::get('/kembali-buku/edit/{id}', 'Perpustakaan\KembaliBukuController@edit');


        Route::get('/sumbang-buku', 'Perpustakaan\SumbangBukuController@index');
        Route::get('/sumbang-buku/table', 'Perpustakaan\SumbangBukuController@datatable');
        Route::post('/sumbang-buku/update', 'Perpustakaan\SumbangBukuController@update');
        Route::get('/sumbang-buku/show/{id}', 'Perpustakaan\SumbangBukuController@show');
        // Route::post('/sumbang-buku/simpan', 'Perpustakaan\SumbangBukuController@simpan');
        Route::get('/sumbang-buku/hapus/{id}', 'Perpustakaan\SumbangBukuController@hapus');
        Route::get('/sumbang-buku/edit/{id}', 'Perpustakaan\SumbangBukuController@edit');

        Route::get('/kehilangan-buku', 'Perpustakaan\KehilanganBukuController@index');
        Route::get('/kehilangan-buku/table', 'Perpustakaan\KehilanganBukuController@datatable');
        Route::post('/kehilangan-buku/update', 'Perpustakaan\KehilanganBukuController@update');
        Route::get('/kehilangan-buku/show/{id}', 'Perpustakaan\KehilanganBukuController@show');
        // Route::post('/kehilangan-buku/simpan', 'Perpustakaan\KehilanganBukuController@simpan');
        Route::get('/kehilangan-buku/hapus/{id}', 'Perpustakaan\KehilanganBukuController@hapus');
        Route::get('/kehilangan-buku/edit/{id}', 'Perpustakaan\KehilanganBukuController@edit');




        //Feedback
        Route::get('/feed', 'FeedController@index');
        Route::get('/feed/table', 'FeedController@datatable');
        Route::get('/feed/hapus', 'FeedController@hapus');

        //Category
        Route::get('/category', 'CategoryController@index');
        Route::get('/category/simpan', 'CategoryController@dosavecategory');
        Route::get('/category/edit', 'CategoryController@doeditcategory');
        Route::get('/category/update', 'CategoryController@doupdatecategory');
        Route::get('/category/hapus', 'CategoryController@dodeletecategory');

        //Setting backgroundheader
        Route::get('/setting/backgroundheader', 'BackgroundheaderController@index');
        Route::post('/setting/backgroundheader/save', 'BackgroundheaderController@save');

        //Setting edit info
        Route::get('/setting/editinfo', 'EditinfoController@index');
        Route::get('/setting/editinfo/save', 'EditinfoController@save');

        //Social
        Route::get('/setting/social', 'SocialController@index');
        Route::get('/setting/social/simpan', 'SocialController@dosavecategory');
        Route::get('/setting/social/edit', 'SocialController@doeditcategory');
        Route::get('/setting/social/update', 'SocialController@doupdatecategory');
        Route::get('/setting/social/hapus', 'SocialController@dodeletecategory');

        //Mutasi Siswa
        Route::get('/mutasisiswa', 'MutasiSiswaController@index');
        Route::get('/mutasisiswatable', 'MutasiSiswaController@datatable');
        Route::post('/simpanmutasisiswa', 'MutasiSiswaController@simpan');
        Route::get('/editmutasisiswa', 'MutasiSiswaController@edit');
        Route::get('/hapusmutasisiswa', 'MutasiSiswaController@hapus');

        //Absensi Siswa
        Route::get('/absensisiswa', 'AbsensiSiswaController@index');
        Route::get('/absensisiswatable', 'AbsensiSiswaController@datatable');

        //Absensi Pegawai
        Route::get('/absensipegawai', 'AbsensiPegawaiController@index');
        Route::get('/absensipegawaitable', 'AbsensiPegawaiController@datatable');

        //Absensi Guru
        Route::get('/absensiguru', 'AbsensiGuruController@index');
        Route::get('/absensigurutable', 'AbsensiGuruController@datatable');
    });
});

//Route untuk user pembeli / penjual
Route::group(['middleware' => 'penjual'], function () {

    //Admin Module
    Route::prefix('penjual')->group(function () {
        Route::get('/home', 'PenjualHomeController@index');

        //Edit info toko
        Route::get('/toko', 'EdittokoController@index');
        Route::post('/toko/save', 'EdittokoController@simpan');

        //List Feedback / Review
        Route::get('/listfeed', 'FeedController@penjualindex');
        Route::get('/listfeed/table', 'FeedController@datatablewtoko');

        //Manage Produk
        Route::get('/produk', 'PenjualProdukController@index');
        Route::get('/produk/tambahproductcontent', 'PenjualProdukController@tambah');
        Route::get('/produk/productcontenttable', 'PenjualProdukController@datatable');
        Route::post('/produk/simpanproductcontent', 'PenjualProdukController@simpan');
        Route::get('/produk/editproductcontent/{id}', 'PenjualProdukController@edit');
        Route::get('/produk/doeditproductcontent', 'PenjualProdukController@doedit');
        Route::get('/produk/removeimageproductcontent', 'PenjualProdukController@removeimage');
        Route::get('/produk/hapusproductcontent', 'PenjualProdukController@hapus');

        //Manage Lelang
        Route::get('/lelang', 'PenjualLelangController@index');
        Route::get('/lelang/table', 'PenjualLelangController@datatable');
        Route::get('/lelang/listbid/{id}', 'PenjualLelangController@listbid');
        Route::get('/lelang/hapus', 'PenjualLelangController@hapus');
        Route::get('/lelang/aktif', 'PenjualLelangController@aktif');
        Route::get('/lelang/nonaktif', 'PenjualLelangController@nonaktif');
        Route::get('/lelang/edit', 'PenjualLelangController@edit');
        Route::post('/lelang/simpan', 'PenjualLelangController@simpan');
        Route::post('/lelang/update', 'PenjualLelangController@update');
        Route::get('/lelang/pemenang', 'PenjualLelangController@pemenang');
        Route::get('/lelang/won', 'PenjualLelangController@won');
        Route::get('/lelang/lelangnotif', 'PenjualLelangController@lelangnotif');

        //List Pesanan
        Route::get('/listorder', 'PenjualListpesananController@index');
        Route::get('/listorder/table', 'PenjualListpesananController@dataTable');
        Route::get('/listorder/cancel', 'PenjualListpesananController@cancel');
        Route::get('/listorder/hapus', 'PenjualListpesananController@hapus');
        Route::get('/listorder/detail', 'PenjualListpesananController@detail');
        Route::get('/listorder/deliver', 'PenjualListpesananController@deliver');
        Route::get('/listorder/deliverdone', 'PenjualListpesananController@deliverdone');
        Route::get('/listorder/showpayment/{id}', 'PenjualListpesananController@showpayment');
        Route::get('/listorder/approve', 'PenjualListpesananController@approve');
        Route::get('/listorder/pesanannotif', 'PenjualListpesananController@pesanannotif');
    }
    );
});
