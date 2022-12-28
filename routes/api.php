<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('api')->group(function () {
    Route::any('login', 'loginController@loginApi');
    Route::any('profile', 'loginController@profileApi');

    //Mutasi Siswa
    Route::get('/iistmutasisiswa', 'MutasiSiswaController@getMutasiSiswaJson');
    Route::post('/simpanmutasisiswa', 'MutasiSiswaController@simpan');
    Route::get('/editmutasisiswa', 'MutasiSiswaController@edit');
    Route::get('/hapusmutasisiswa', 'MutasiSiswaController@hapus');

    //Absensi Siswa
    Route::get('/totalabsensiswa', 'AbsensiSiswaController@getTotalKehadiran');
    Route::get('/listabsensisiswa', 'AbsensiSiswaController@getAbsensiSiswaJson');
    Route::post('/simpanabsensisiswa', 'AbsensiSiswaController@simpan');

    //Absensi Pegawai
    Route::get('/totalabsenpegawai', 'AbsensiPegawaiController@getTotalKehadiran');
    Route::get('/listabsensipegawai', 'AbsensiPegawaiController@getAbsensiPegawaiJson');
    Route::post('/simpanabsensipegawai', 'AbsensiPegawaiController@simpan');

    //Kepala sekolah
    Route::get('/iistkepalasekolah', 'KepalaSekolahController@getKepalaSekolahJson');

    //Absensi Kepala Sekolah
    Route::get('/totalabsenkepalasekolah', 'AbsensiKepalaSekolahController@getTotalKehadiran');
    Route::get('/listabsensikepalasekolah', 'AbsensiKepalaSekolahController@getAbsensiKepalaSekolahJson');
    Route::post('/simpanabsensikepalasekolah', 'AbsensiKepalaSekolahController@simpan');

    //Absensi Guru
    Route::get('/totalabsenguru', 'AbsensiGuruController@getTotalKehadiran');
    Route::get('/listabsensiguru', 'AbsensiGuruController@getAbsensiGuruJson');
    Route::post('/simpanabsensiguru', 'AbsensiGuruController@simpan');

    //Kartu Digital
    Route::get('/kartudigital', 'KartuDigitalController@getKartuDigitalJson');
    Route::get('/kartudigitalById', 'KartuDigitalController@generateJson');

    //Dompet Digital
    Route::get('/dompetdigital', 'DompetDigitalController@getDompetDigitalJson');
    Route::post('/topupdompetdigital', 'DompetDigitalController@topup');

    //Approve Dompet Digital
    Route::get('/approvedompetdigital', 'ApproveDompetDigitalController@getApproveDompetDigitalJson');
    Route::get('/actionapprovedompetdigital', 'ApproveDompetDigitalController@action');

    //Berita kelas/sekolah
    Route::get('/berita', 'BeritaKelasController@getData');

    //Kegiatan Osis
    Route::post('/kegiatan-osis', 'KegiatanOsisController@insertOrUpdate');
    Route::get('/kegiatan-osis', 'KegiatanOsisController@getData');
    Route::delete('/kegiatan-osis/{id}', 'KegiatanOsisController@delete');

    //Ekstrakulikuler
    Route::post('/ekstrakulikuler', 'EkstrakulikulerController@insertOrUpdate');
    Route::get('/ekstrakulikuler', 'EkstrakulikulerController@getData');
    Route::delete('/ekstrakulikuler/{id}', 'EkstrakulikulerController@delete');

    //Fasilitas Sekolah
    Route::post('/list-fasilitas', 'Fasilitas\ListFasilitasController@simpan');
    Route::get('/list-fasilitas', 'Fasilitas\ListFasilitasController@getData');
    Route::delete('/list-fasilitas/{id}', 'Fasilitas\ListFasilitasController@delete');

    //Pinjam Fasilitas Sekolah
    Route::post('/pinjam-fasilitas', 'Fasilitas\PinjamFasilitasController@ajukanPeminjaman');
    Route::post('/pinjam-fasilitas/acc', 'Fasilitas\PinjamFasilitasController@APIaccPeminjaman');
    Route::get('/pinjam-fasilitas', 'Fasilitas\PinjamFasilitasController@getData');
    Route::delete('/pinjam-fasilitas/{id}', 'Fasilitas\PinjamFasilitasController@delete');

    //Raport/Nilai Pembelajaran
    Route::get('/nilai-pembelajaran', 'NilaiPembelajaranController@getData');
    Route::post('/nilai-pembelajaran', 'NilaiPembelajaranController@insertOrUpdate');
    Route::get('/nilai-pembelajaran/acc', 'NilaiPembelajaranController@APIaccNilai');

    //Kelas
    Route::get('/kelas', 'KelasController@getData');

    //Mapel
    Route::get('/mata-pelajaran', 'MataPelajaranController@getData');

    //Jadwal Sekolah
    Route::get('/jadwal-sekolah', 'JadwalSekolahController@getData');
    Route::get('/jadwal-kelas', 'JadwalPembelajaranController@getData');
    Route::get('/jadwal-kelas-sekarang', 'JadwalPembelajaranController@getJadwalSekarang');

    //Perpustakaan
    Route::get('/katalog-buku', 'Perpustakaan\KatalogBukuController@getData');
    Route::get('/kategori-buku', 'Perpustakaan\KategoriBukuController@getData');
    Route::get('/sumbang-buku', 'Perpustakaan\SumbangBukuController@getData');
    Route::get('/kantin', 'BayarKantinController@getData');
    Route::post('/pinjam-buku', 'Perpustakaan\PinjamBukuController@insertData');
    Route::get('/pinjam-buku', 'Perpustakaan\PinjamBukuController@getData');
    Route::get('/kembali-buku', 'Perpustakaan\KembaliBukuController@getData');
    Route::post('/kembali-buku', 'Perpustakaan\KembaliBukuController@APIinsertData');
    Route::get('/kembali-buku/acc', 'Perpustakaan\KembaliBukuController@APIaccKembali');
    Route::get('/pinjam-buku/acc', 'Perpustakaan\PinjamBukuController@APIaccPinjam');
    Route::delete('/pinjam-buku/{id}', 'Perpustakaan\PinjamBukuController@delete');
    Route::post('/sumbang-buku', 'Perpustakaan\SumbangBukuController@simpan');
    Route::post('/katalog-buku', 'Perpustakaan\KatalogBukuController@insertOrUpdate');
    Route::delete('/sumbang-buku/{id}', 'Perpustakaan\SumbangBukuController@delete');
    Route::delete('/katalog-buku/{id}', 'Perpustakaan\KatalogBukuController@delete');
    Route::get('/sumbang-buku/acc', 'Perpustakaan\SumbangBukuController@APIaccSumbang');
    Route::get('/kehilangan-buku', 'Perpustakaan\KehilanganBukuController@getData');
    Route::post('/kehilangan-buku', 'Perpustakaan\KehilanganBukuController@APIinsertData');

    //Kantin
    Route::post('/bayar-koperasi', 'Koperasi\TransaksiController@APIbayar');
    Route::post('/bayar-kantin', 'BayarKantinController@APIbayar');
    Route::post('/tambah-transaksi', 'BayarKantinController@APIbayar');
    Route::post('/kantin', 'BayarKantinController@getData');
    Route::get('/transaksi-kantin', 'TransaksiKantinController@getData');
    Route::get('/transaksi-koperasi', 'Koperasi\TransaksiController@getData');
    Route::post('/transaksi-kantin', 'TransaksiKantinController@APIupdate');
    Route::delete('/transaksi-kantin/{id}', 'TransaksiKantinController@delete');

    // list koperasi
    Route::post('/list-koperasi', 'Koperasi\ListController@insertOrUpdate');
    Route::delete('/list-koperasi/{id}', 'Koperasi\ListController@delete');
    Route::get('/list-koperasi', 'Koperasi\ListController@getData');
    Route::get('/list-pembelian', 'Koperasi\TransaksiController@listPembelian');
    Route::delete('/list-pembelian/{id}', 'Koperasi\TransaksiController@delete');
    Route::post('/list-pembelian', 'Koperasi\TransaksiController@APIupdate');

    //OSIS
    Route::get('/calon-osis/daftar', 'SiswaController@APIDaftarOsis');
    Route::get('/calon-osis/acc', 'SiswaController@APIAccPermintaan');
    Route::get('/calon-osis/permintaan', 'SiswaController@listPermintaan');
    Route::get('/anggota-osis', 'SiswaController@listAnggota');

    //Keuangan
    Route::get('/keuangan', 'KeuanganController@getKeuanganJson');
    Route::get('/kategorikeuangan', 'KategoriKeuanganController@getKategoriKeuanganJson');
    Route::post('/simpankeuangan', 'KeuanganController@simpan');

    Route::get('/ppdb', 'SiswaController@ppdb');
    Route::get('/list-pendaftaran', 'SiswaController@getPpdb');

    Route::get('/total-notifikasi', 'NotifikasiController@count_notifikasi');
    Route::get('/get-notifikasi', 'NotifikasiController@get_notifikasi');

    //withdraw
    Route::post('/withdraw', 'WithdrawController@APIinsertData');
    Route::get('/getDataWithdraw', 'WithdrawController@getWithdrawJson');

    //update username or password user
    Route::post('/update-user', 'SiswaController@updateProfileUser');

    //List siswa by id
    Route::get('/listsiswabykelas', 'SiswaController@getSiswaByKelas');

    //List siswa by walimurid
    Route::get('/listsiswabywalimurid', 'SiswaController@getSiswaByWalimurid');

    //getpembinaosis
    Route::get('/pembina-osis', 'EkstrakulikulerController@getPembina');

    //set Nilai Rata
    Route::get('/nilai-pembelajaran/set-rata', 'NilaiPembelajaranController@setRata');

    //koperasi
    Route::get('/saldo-koperasi', 'Koperasi\ListController@countSaldo');

    //List Siswa
    Route::get('/listsiswa', 'SiswaController@getSiswa');

    //List Guru
    Route::get('/listguru', 'GuruController@getGuru');

    //List Pegawai
    Route::get('/listpegawai', 'PegawaiController@getPegawai');

});
