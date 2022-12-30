<?php

Route::get('/login', function () {
    $user = \Auth::user();
    if($user == null) {
      return view('auth.login');
    } else {
        return redirect()->route('homeadmin');
    }
}
)->name('logindashboard');


// Route::get('/', function () {
//     return view('homepage.index');
// }
// );

Route::get('/', 'PublicController@homepage')->name('Public');
Route::get('/ppdb-register', 'PublicController@registerWalimurid')->name('registerWalimurid');
Route::post('/ppdb-register-simpan', 'PublicController@registerWalimuridSimpan');

Route::get('/ppdb-register-murid', 'PublicController@registerMurid')->name('registerMurid');
Route::post('/ppdb-register-murid-simpan', 'PublicController@registerMuridSimpan');

Route::get('/ppdb-login', 'PublicController@loginWalimurid')->name('loginWalimurid');
Route::get('/ppdb-login-walimurid', 'PublicController@authenticate')->name('ppdbloginWalimurid');

Route::get('loginadmin', 'loginController@authenticate')->name('loginadmin');
Route::get('logout', 'HomeController@logout')->name('logoutadmin');

Route::get('/generatekartudigital', 'KartuDigitalController@generate');

//Route untuk user admin
Route::group(['middleware' => 'auth'], function () {

    //Admin Module
    Route::prefix('admin')->group(function () {

        Route::get('/home', 'HomeController@index')->name('homeadmin');
        Route::get('/setPpdb', 'SiswaController@setPpdb');

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

        //siswa
        Route::get('/anggota-osis', 'SiswaController@osisindex');
        Route::post('/anggota-osis/tambah', 'SiswaController@tambahAnggotaOsis');
        Route::get('/anggota-osis/table', 'SiswaController@osisdatatable');
        Route::get('/anggota-osis/keluar/{id}', 'SiswaController@osiskeluar');
        Route::get('/calon-osis', 'SiswaController@calonosisindex');
        Route::get('/calon-osis/table', 'SiswaController@calonosisdatatable');
        Route::get('/calon-osis/acc/{id}', 'SiswaController@accPermintaan');
        Route::get('/calon-osis/daftar', 'SiswaController@daftarOsis');

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

        //Dinas Pendidikan
        Route::get('/dinas-pendidikan', 'DinasPendidikanController@index');
        Route::post('/dinas-pendidikan/simpan', 'DinasPendidikanController@simpan');
        Route::get('/dinas-pendidikan/hapus/{id}', 'DinasPendidikanController@hapus');
        Route::get('/dinas-pendidikan/table', 'DinasPendidikanController@datatable');
        Route::post('/dinas-pendidikan/update', 'DinasPendidikanController@update');
        Route::get('/dinas-pendidikan/edit/{id}', 'DinasPendidikanController@edit');

        //Kepala Sekolah
        Route::get('/kepala-sekolah', 'KepalaSekolahController@index');
        Route::post('/kepala-sekolah/simpan', 'KepalaSekolahController@simpan');
        Route::get('/kepala-sekolah/hapus/{id}', 'KepalaSekolahController@hapus');
        Route::get('/kepala-sekolah/table', 'KepalaSekolahController@datatable');
        Route::post('/kepala-sekolah/update', 'KepalaSekolahController@update');
        Route::get('/kepala-sekolah/edit/{id}', 'KepalaSekolahController@edit');

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

         //Jadwal Sekolah
        Route::get('/jadwal-sekolah', 'JadwalSekolahController@index');
        Route::post('/jadwal-sekolah/simpan', 'JadwalSekolahController@simpan');
        Route::get('/jadwal-sekolah/table', 'JadwalSekolahController@datatable');
        Route::post('/jadwal-sekolah/update', 'JadwalSekolahController@update');
        Route::get('/jadwal-sekolah/hapus/{id}', 'JadwalSekolahController@hapus');
        Route::get('/jadwal-sekolah/edit/{id}', 'JadwalSekolahController@edit');

        //PPDB
        Route::get('/ppdb/list', 'SiswaController@ppdbindex');
        Route::get('/ppdb/table', 'SiswaController@datatablePpdb');

        Route::get('/ppdb/acc/{id}', 'SiswaController@accPpdb');
        Route::get('/ppdb/tolak/{id}', 'SiswaController@tolakPpdb');

        //Berita Sekolah
        Route::get('/berita-sekolah', 'BeritaSekolahController@index');
        Route::post('/berita-sekolah/simpan', 'BeritaSekolahController@simpan');
        Route::get('/berita-sekolah/hapus/{id}', 'BeritaSekolahController@hapus');
        Route::get('/berita-sekolah/table', 'BeritaSekolahController@datatable');
        Route::post('/berita-sekolah/update', 'BeritaSekolahController@update');
        Route::get('/berita-sekolah/edit/{id}', 'BeritaSekolahController@edit');
        Route::get('/berita-sekolah/show/{id}', 'BeritaSekolahController@show');

        //Berita Kelas
        Route::get('/berita-kelas', 'BeritaKelasController@index');
        Route::post('/berita-kelas/simpan', 'BeritaKelasController@simpan');
        Route::get('/berita-kelas/hapus/{id}', 'BeritaKelasController@hapus');
        Route::get('/berita-kelas/table', 'BeritaKelasController@datatable');
        Route::post('/berita-kelas/update', 'BeritaKelasController@update');
        Route::get('/berita-kelas/edit/{id}', 'BeritaKelasController@edit');
        Route::get('/berita-kelas/show/{id}', 'BeritaKelasController@show');

        //Bayar QRCode
        Route::get('/bayar-kantin', 'BayarKantinController@index');
        Route::get('/bayar-kantin/table', 'BayarKantinController@datatable');
        Route::get('/bayar-kantin/{id}', 'BayarKantinController@toBayar');
        Route::post('/bayar-kantin/bayar', 'BayarKantinController@bayar');
        Route::post('/bayar-kantin/simpan', 'BayarKantinController@simpan');
        Route::get('/bayar-kantin/hapus/{id}', 'BayarKantinController@hapus');
        Route::post('/bayar-kantin/update', 'BayarKantinController@update');
        Route::get('/bayar-kantin/edit/{id}', 'BayarKantinController@edit');
        Route::get('/bayar-kantin/show/{id}', 'Koperasi\TransaksiController@show');


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
        Route::get('/kembali-buku/acc', 'Perpustakaan\KembaliBukuController@accKembali');
        Route::get('/pinjam-buku/acc', 'Perpustakaan\PinjamBukuController@accPinjam');

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
        Route::post('/pinjam-buku/simpan', 'Perpustakaan\PinjamBukuController@simpan');
        Route::get('/pinjam-buku/hapus/{id}', 'Perpustakaan\PinjamBukuController@hapus');
        Route::get('/pinjam-buku/edit/{id}', 'Perpustakaan\PinjamBukuController@edit');

        Route::get('/kembali-buku', 'Perpustakaan\KembaliBukuController@index');
        Route::get('/kembali-buku/table', 'Perpustakaan\KembaliBukuController@datatable');
        Route::post('/kembali-buku/update', 'Perpustakaan\KembaliBukuController@update');
        Route::get('/kembali-buku/show/{id}', 'Perpustakaan\KembaliBukuController@show');
        Route::post('/kembali-buku/simpan', 'Perpustakaan\KembaliBukuController@simpan');
        Route::get('/kembali-buku/hapus/{id}', 'Perpustakaan\KembaliBukuController@hapus');
        Route::get('/kembali-buku/edit/{id}', 'Perpustakaan\KembaliBukuController@edit');
        Route::post('/kembalikanBuku', 'Perpustakaan\KembaliBukuController@insertData');

        Route::get('/sumbang-buku', 'Perpustakaan\SumbangBukuController@index');
        Route::get('/sumbang-buku/table', 'Perpustakaan\SumbangBukuController@datatable');
        Route::post('/sumbang-buku/update', 'Perpustakaan\SumbangBukuController@update');
        Route::get('/sumbang-buku/show/{id}', 'Perpustakaan\SumbangBukuController@show');
        Route::post('/sumbang-buku/simpan', 'Perpustakaan\SumbangBukuController@simpan');
        Route::get('/sumbang-buku/hapus/{id}', 'Perpustakaan\SumbangBukuController@hapus');
        Route::get('/sumbang-buku/edit/{id}', 'Perpustakaan\SumbangBukuController@edit');
        Route::get('/sumbang-buku/acc', 'Perpustakaan\SumbangBukuController@accSumbang');

        Route::get('/kehilangan-buku', 'Perpustakaan\KehilanganBukuController@index');
        Route::get('/kehilangan-buku/table', 'Perpustakaan\KehilanganBukuController@datatable');
        Route::post('/kehilangan-buku/update', 'Perpustakaan\KehilanganBukuController@update');
        Route::get('/kehilangan-buku/show/{id}', 'Perpustakaan\KehilanganBukuController@show');
        Route::post('/kehilangan-buku/simpan', 'Perpustakaan\KehilanganBukuController@simpan');
        Route::get('/kehilangan-buku/hapus/{id}', 'Perpustakaan\KehilanganBukuController@hapus');
        Route::get('/kehilangan-buku/edit/{id}', 'Perpustakaan\KehilanganBukuController@edit');

      //Koperasi
        Route::get('/list-koperasi', 'Koperasi\ListController@index');
        Route::get('/list-koperasi/table', 'Koperasi\ListController@datatable');
        Route::post('/list-koperasi/update', 'Koperasi\ListController@update');
        Route::get('/list-koperasi/show/{id}', 'Koperasi\ListController@show');
        Route::post('/list-koperasi/simpan', 'Koperasi\ListController@simpan');
        Route::get('/list-koperasi/hapus/{id}', 'Koperasi\ListController@hapus');
        Route::get('/list-koperasi/edit/{id}', 'Koperasi\ListController@edit');

        Route::get('/transaksi-koperasi', 'Koperasi\TransaksiController@index');
        Route::get('/transaksi-koperasi/table', 'Koperasi\TransaksiController@datatable');
        Route::post('/transaksi-koperasi/update', 'Koperasi\TransaksiController@update');
        Route::get('/transaksi-koperasi/show/{id}', 'Koperasi\TransaksiController@show');
        Route::post('/transaksi-koperasi/simpan', 'Koperasi\TransaksiController@simpan');
        Route::get('/transaksi-koperasi/hapus/{id}', 'Koperasi\TransaksiController@hapus');
        Route::get('/transaksi-koperasi/edit/{id}', 'Koperasi\TransaksiController@edit');
        Route::get('/transaksi-koperasi/show/{id}', 'Koperasi\TransaksiController@show');

        //Kegiatan OSIS
        Route::get('/kegiatan-osis', 'KegiatanOsisController@index');
        Route::get('/kegiatan-osis/table', 'KegiatanOsisController@datatable');
        Route::post('/kegiatan-osis/update', 'KegiatanOsisController@update');
        Route::post('/kegiatan-osis/simpan', 'KegiatanOsisController@simpan');
        Route::get('/kegiatan-osis/hapus/{id}', 'KegiatanOsisController@hapus');
        // Route::get('/kegiatan-osis/set-acc/{id}', 'KegiatanOsisController@acc');
        Route::get('/kegiatan-osis/edit/{id}', 'KegiatanOsisController@edit');


        //Ekstrakulikuler
        Route::get('/ekstrakulikuler', 'EkstrakulikulerController@index');
        Route::get('/ekstrakulikuler/table', 'EkstrakulikulerController@datatable');
        Route::post('/ekstrakulikuler/update', 'EkstrakulikulerController@update');
        Route::post('/ekstrakulikuler/simpan', 'EkstrakulikulerController@simpan');
        Route::get('/ekstrakulikuler/hapus/{id}', 'EkstrakulikulerController@hapus');
        Route::get('/ekstrakulikuler/edit/{id}', 'EkstrakulikulerController@edit');

        //Peminjaman Fasilitas
        Route::get('/list-fasilitas', 'Fasilitas\ListFasilitasController@index');
        Route::get('/list-fasilitas/table', 'Fasilitas\ListFasilitasController@datatable');
        Route::post('/list-fasilitas/update', 'Fasilitas\ListFasilitasController@update');
        Route::post('/list-fasilitas/simpan', 'Fasilitas\ListFasilitasController@simpan');
        Route::get('/list-fasilitas/hapus/{id}', 'Fasilitas\ListFasilitasController@hapus');
        Route::get('/list-fasilitas/edit/{id}', 'Fasilitas\ListFasilitasController@edit');
        Route::get('/pinjam-fasilitas/acc', 'Fasilitas\PinjamFasilitasController@accPeminjaman');

        Route::get('/pinjam-fasilitas', 'Fasilitas\PinjamFasilitasController@index');
        Route::get('/pinjam-fasilitas/table', 'Fasilitas\PinjamFasilitasController@datatable');
        Route::post('/pinjam-fasilitas/update', 'Fasilitas\PinjamFasilitasController@update');
        Route::post('/pinjam-fasilitas/simpan', 'Fasilitas\PinjamFasilitasController@simpan');
        Route::get('/pinjam-fasilitas/hapus/{id}', 'Fasilitas\PinjamFasilitasController@hapus');
        Route::get('/pinjam-fasilitas/edit/{id}', 'Fasilitas\PinjamFasilitasController@edit');

        //Pembelajaran Siswa
        Route::get('/nilai-pembelajaran', 'NilaiPembelajaranController@index');
        Route::get('/nilai-pembelajaran/table', 'NilaiPembelajaranController@datatable');
        Route::post('/nilai-pembelajaran/update', 'NilaiPembelajaranController@update');
        Route::post('/nilai-pembelajaran/simpan', 'NilaiPembelajaranController@simpan');
        Route::get('/nilai-pembelajaran/hapus/{id}', 'NilaiPembelajaranController@hapus');
        Route::get('/nilai-pembelajaran/edit/{id}', 'NilaiPembelajaranController@edit');
        Route::get('/nilai-pembelajaran/{tipe}', 'NilaiPembelajaranController@accOrUnacc');
        Route::get('/nilai-pembelajaran/cetak/raport', 'NilaiPembelajaranController@accNilai');

        //Kartu Figital
        Route::get('/kartudigital', 'KartuDigitalController@index');
        Route::get('/kartudigitaltable', 'KartuDigitalController@datatable');

        Route::get('/kartudigitalsaya', 'KartuDigitalController@indexsaya');

        // Kategori Keuangan
        Route::get('/kategori-keuangan', 'KategoriKeuanganController@index');
        Route::get('/kategori-keuangan-table', 'KategoriKeuanganController@datatable');
        Route::post('/simpan-kategori-keuangan', 'KategoriKeuanganController@simpan');
        Route::get('/edit-kategori-keuangan', 'KategoriKeuanganController@edit');
        Route::get('/hapus-kategori-keuangan', 'KategoriKeuanganController@hapus');

        //  Keuangan
        Route::get('/data-keuangan', 'KeuanganController@index');
        Route::get('/data-keuangan-table', 'KeuanganController@datatable');
        Route::post('/simpan-data-keuangan', 'KeuanganController@simpan');
        Route::get('/edit-data-keuangan', 'KeuanganController@edit');
        Route::get('/hapus-data-keuangan', 'KeuanganController@hapus');

        //Mutasi Siswa
        Route::get('/mutasisiswa', 'MutasiSiswaController@index');
        Route::get('/mutasisiswatable', 'MutasiSiswaController@datatable');
        Route::post('/simpanmutasisiswa', 'MutasiSiswaController@simpan');
        Route::get('/editmutasisiswa', 'MutasiSiswaController@edit');
        Route::get('/hapusmutasisiswa', 'MutasiSiswaController@hapus');

        //Absensi Siswa
        Route::get('/absensisiswa', 'AbsensiSiswaController@index');
        Route::get('/absensisiswatable', 'AbsensiSiswaController@datatable');

        //Absensi Siswa Saya
        Route::get('/absensisiswasaya', 'AbsensiSiswaController@indexsaya');

        //Absensi Pegawai
        Route::get('/absensipegawai', 'AbsensiPegawaiController@index');
        Route::get('/absensipegawaitable', 'AbsensiPegawaiController@datatable');

        //Absensi Pegawai Saya
        Route::get('/absensipegawaisaya', 'AbsensiPegawaiController@indexsaya');

        //Absensi Kepala Sekolah
        Route::get('/absensikepalasekolah', 'AbsensiKepalaSekolahController@index');
        Route::get('/absensikepalasekolahtable', 'AbsensiKepalaSekolahController@datatable');

        //Absensi Kepala Sekolah Saya
        Route::get('/absensikepalasekolahsaya', 'AbsensiKepalaSekolahController@indexsaya');

        //Absensi Guru
        Route::get('/absensiguru', 'AbsensiGuruController@index');
        Route::get('/absensigurutable', 'AbsensiGuruController@datatable');

        //Absensi Guru Saya
        Route::get('/absensigurusaya', 'AbsensiGuruController@indexsaya');

        //Dompet Digital
        Route::get('/dompetdigital', 'DompetDigitalController@index');
        Route::get('/dompetdigitaltable', 'DompetDigitalController@datatable');
        Route::post('/topupdompetdigital', 'DompetDigitalController@topup');

        //Dompet Digital
        Route::get('/dompetdigitalsaya', 'DompetDigitalController@indexsaya');

        //Approve Dompet Digital
        Route::get('/approvedompetdigital', 'ApproveDompetDigitalController@index');
        Route::get('/approvedompetdigitaltable', 'ApproveDompetDigitalController@datatable');
        Route::get('/actionapprovedompetdigital', 'ApproveDompetDigitalController@action');

        //Withdraw kantin
        Route::get('/withdraw', 'WithdrawController@index');
        Route::post('/withdraw', 'WithdrawController@insertData');
        Route::get('/withdraw/table', 'WithdrawController@datatable');
        Route::get('/approve-withdraw', 'WithdrawController@action');
    });
});
