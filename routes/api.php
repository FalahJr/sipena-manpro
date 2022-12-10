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
});
