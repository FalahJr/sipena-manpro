<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', 'HomepageController@index');
// Route::get('/getinfo', 'HomepageController@getinfo');
// Route::get('/admin', 'HomeController@checklogin');
// Route::get('/product', 'ProductController@index');
// Route::get('/product/detail/{url_segment}', 'ProductController@show')->name('detailproduct');
// Route::get('/lelang', 'LelangController@index');
// Route::get('/lelang/detail/{url_segment}', 'LelangController@show')->name('detaillelang');
// Route::get('/contact', 'KontakController@index');
// Route::get('loginmember', 'MemberController@login')->name('loginmember');
// Route::get('/forgot', 'MemberController@forgot');
// Route::get('/logoutmember', 'MemberController@logout')->name('logoutmember');
// Route::post('/registermember', 'MemberController@register')->name('registermember');
// Route::post('/editmember', 'MemberController@edit')->name('editmember');
// Route::get('/logoutmemberjson', 'MemberController@logoutjson');

// Route::get('/pembeli/profile', 'MemberController@profile')->name('profilemember');
// Route::get('/pembeli/history', 'HistoryController@index')->name('historymember');
// Route::post('/pembeli/pay', 'HistoryController@pay');

// Route::get('/lelangupdate', 'LelangController@lelangupdate');

// // Route::get('/pembeli/profile', 'MemberController@profile')->name('profilemember');
// Route::get('/lelangupdate', 'LelangController@lelangupdate');
// Route::get('/countcart', 'CartController@countcart');
// Route::get('/addcart', 'CartController@addcart');
// Route::get('/opencart', 'CartController@opencart');
// Route::get('/deletecart', 'CartController@deletecart');
// Route::post('/admin/toko/simpan', 'TokoController@simpan');

// Route::get('/viewcart', 'CartController@viewcart');
// Route::get('/changetoko', 'CartController@changetoko');
// Route::post('/checkout', 'CartController@checkout');

// Route::get('/chat', 'ChatController@index');
// Route::get('/listroom', 'ChatController@listroom');
// Route::get('/countchat', 'ChatController@countchat');
// Route::get('/listchat', 'ChatController@listchat');
// Route::get('/sendchat', 'ChatController@sendchat');
// Route::get('/newchat', 'ChatController@newchat');
// Route::post('/sendimgchat', 'ChatController@sendimgchat');

// Route::post('pembeli/inputulasan', 'HistoryController@inputulasan');

// Route::get('/toko/{id}', 'ProfileTokoController@index')->name('profilToko');

// Route::get('/updateprice', 'LelangController@updateprice');

// Route::get('/addbid', 'LelangController@addbid');

// Route::post('/checkoutlelang', 'LelangController@checkoutlelang');

// Route::get('/notif', 'HomeController@notif');

// Route::get('/feed/detail', 'FeedController@detailfeed');

//Route untuk umum

// use Illuminate\Routing\Route;

Route::group(['middleware' => 'guest'], function () {

    Route::get('/', function () {
        return view('auth.login');
    }
    )->name('adminlogin');

    Route::get('loginadmin', 'loginController@authenticate')->name('loginadmin');
});

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
