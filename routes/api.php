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

    //Mutasi Siswa
    Route::get('/iistmutasisiswa', 'MutasiSiswaController@getMutasiSiswaJson');
    Route::post('/simpanmutasisiswa', 'MutasiSiswaController@simpan');
    Route::get('/editmutasisiswa', 'MutasiSiswaController@edit');
    Route::get('/hapusmutasisiswa', 'MutasiSiswaController@hapus');

    //Absensi Siswa
    Route::get('/listabsensisiswa', 'AbsensiSiswaController@getMutasiSiswaJson');
    Route::post('/simpanabsensisiswa', 'AbsensiSiswaController@simpan');

    //Absensi Pegawai
    Route::get('/listabsensipegawai', 'AbsensiPegawaiController@getMutasiPegawaiJson');
    Route::post('/simpanabsensipegawai', 'AbsensiPegawaiController@simpan');

    //Absensi Guru
    Route::get('/listabsensiguru', 'AbsensiGuruController@getMutasiGuruJson');
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

    //Kegitan kelas/sekolah
    Route::post('/kegiatan-osis', 'KegiatanOsisController@insertOrUpdate');
    Route::get('/kegiatan-osis', 'KegiatanOsisController@getData');
    Route::delete('/kegiatan-osis/{id}', 'KegiatanOsisController@delete');

    //Fasilitas Sekolah
    Route::post('/list-fasilitas', 'Fasilitas\ListFasilitasController@simpan');
    Route::get('/list-fasilitas', 'Fasilitas\ListFasilitasController@getData');
    Route::delete('/list-fasilitas/{id}', 'Fasilitas\ListFasilitasController@delete');

    //Pinjam Fasilitas Sekolah
    Route::post('/pinjam-fasilitas', 'Fasilitas\PinjamFasilitasController@ajukanPeminjaman');
    Route::post('/pinjam-fasilitas/acc', 'Fasilitas\PinjamFasilitasController@accPeminjaman');
    Route::get('/pinjam-fasilitas', 'Fasilitas\PinjamFasilitasController@getData');
    Route::delete('/pinjam-fasilitas/{id}', 'Fasilitas\PinjamFasilitasController@delete');

    //Raport/Nilai Pembelajaran
    Route::get('/nilai-pembelajaran', 'NilaiPembelajaranController@getData');
    Route::post('/nilai-pembelajaran', 'NilaiPembelajaranController@insertOrUpdate');
    Route::get('/nilai-pembelajaran/acc', 'NilaiPembelajaranController@accNilai');

    //Kelas
    Route::get('/kelas', 'KelasController@getData');

    //Mapel
    Route::get('/mata-pelajaran', 'MataPelajaranController@getData');
});
