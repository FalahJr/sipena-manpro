<?php

namespace App\Http\Controllers\Perpustakaan;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Account;

use App\Authentication;

use Auth;

use Carbon\Carbon;

use Session;

use DB;

use File;

use Yajra\Datatables\Datatables;

use Response;

class KembaliBukuController extends Controller
{
  public function index()
  { 
    // ambil user yang meminjam buku
    $users = DB::table("perpus_peminjaman")
    ->where('is_kembali','N')
    ->whereNotNull('pegawai_id')
    ->join('user', 'perpus_peminjaman.user_id', '=', 'user.id')
    ->select('user.username','user.id')
    ->get();
    $employees = DB::table("pegawai")->where("is_perpus","Y")->get();
    return view('kembali_buku.index', compact('users','employees'));
  }

  public function datatable()
  {
    $data = DB::table('perpus_peminjaman')->where("is_kembali","Y")
      ->get();

    // return $data;
    // $xyzab = collect($data);
    // return $xyzab;
    // return $xyzab->i_price;
    return Datatables::of($data)
    //   ->addColumn("image", function ($data) {
    //     return '<div> <img src="' . url('/') . '/' . $data->profile_picture . '" style="height: 100px; width:100px; border-radius: 0px;" class="img-responsive"> </img> </div>';
    //   })
      ->addColumn('aksi', function ($data) {
        return  '<div class="btn-group">' .
          '<a href="kembali-buku/edit/' . $data->id . '" class="btn btn-info btn-lg">'.
          '<label class="fa fa-pencil-alt"></label></a>' .
          '<a href="/admin/kembali-buku/hapus/'.$data->id.'" class="btn btn-danger btn-lg" title="hapus">' .
          '<label class="fa fa-trash"></label></a>' .
          '</div>';
      })->addColumn('buku', function ($data) {
        $items = DB::table("perpus_peminjaman_katalog")->where("perpus_peminjaman_id", $data->id)->get();
        $urlBook = null;
        foreach($items as $item){
          $katalog = DB::table("perpus_katalog")->where("id", $item->perpus_katalog_id)->first();
          $urlBook .= '<a href="javascript:void(0)" data-id="'.$katalog->id.'" class="showBook" title="show">' . $katalog->judul .'</a><br>';
        }

        return $urlBook;

      })->addColumn('pegawai_id', function ($data) {
        if($data->pegawai_id){
        $employee = DB::table("pegawai")->where("id",$data->pegawai_id)->first()->nama_lengkap;
        return $employee;
      }else{
        return '<span class="badge badge-warning">'.
        'PENDING</span>';
        }
      })->addColumn('user', function ($data) {
        $user = DB::table("user")->where("id", $data->user_id)->first();
        return $user->username;
      })
      ->rawColumns(['aksi', 'buku','pegawai_id','user'])
      ->addIndexColumn()
      ->make(true);
  }

  public function show($id){
    $data = DB::table("perpus_katalog")->where("id",$id)->first();
    return response()->json(['data'=>$data]);
  }

  public function simpan(Request $req)
  {
      try {
        $peminjaman = DB::table("perpus_peminjaman")->where("user_id",$req->user_id)->first();
        
        DB::table("perpus_peminjaman")->where("user_id",$req->user_id)->update(['is_lunas'=>'Y','is_kembali'=>'Y','pegawai_id'=>$req->pegawai_id]);

        $perpus_katalog = DB::table("perpus_peminjaman_katalog")
        ->where('perpus_peminjaman_id',$peminjaman->id)
        ->get();
    
        foreach($perpus_katalog as $katalog){
          DB::table("perpus_katalog")->where('id',$katalog->perpus_katalog_id)->increment('stok_buku',1);
        }

        DB::commit();

        return response()->json(["status" => 1]);
      } catch (\Exception $e) {
        DB::rollback();
        return response()->json(["status" => 7, "message" => "aman"]);
      }
  }

  public function hapus($id)
  {
    DB::table("perpus_peminjaman_katalog")
    ->where('perpus_peminjaman_id',$id)
    ->delete();

    DB::table("perpus_peminjaman")
        ->where('id',$id)
        ->delete();

      return back()->with(['success' => 'Data berhasil dihapus']);
  }

  public function edit($id)
  {
    $books = array(0=>null,1=>null,2=>null);
    $data = DB::table("perpus_peminjaman")->where("id", $id)->first();
    $pinjamKatalogs = DB::table("perpus_peminjaman_katalog")->where("perpus_peminjaman_id", $id)->get();
    $i=0;
    foreach($pinjamKatalogs as $katalogs){
      $books[$i] = DB::table("perpus_katalog")->where("id", $katalogs->perpus_katalog_id)->first()->id;
      $i++;
    }
    $employees = DB::table("pegawai")->where("is_perpus","Y")->get();
    $employee_id = DB::table("pegawai")->where("id",$data->pegawai_id)->first()->id;
    $users = DB::table("user")->get();
    $user_id = DB::table("user")->where("id",$data->user_id)->first()->id;
    $items = DB::table("perpus_katalog")->get();
    // dd($data);
    return view("kembali_buku.edit", compact('data','books','items','employees','employee_id','users','user_id'));
    
  }

  public function update(Request $req)
  {
    $this->validate($req,[
      'perpus_katalog_id' => 'required|max:3',
      'tanggal_peminjaman' => 'required|max:255',
      'tanggal_pengembalian' => 'required|max:255',
      'pegawai_id' => 'required|max:255',
    ]);
    DB::table("perpus_peminjaman_katalog")->where('perpus_peminjaman_id',$req->id)->delete();
    foreach($req->perpus_katalog_id as $idKatalog){
      DB::table("perpus_peminjaman_katalog")->insert([
        "perpus_peminjaman_id" => $req->id,
        "perpus_katalog_id" => $idKatalog,
      ]);
    }

    DB::table("perpus_peminjaman")->where("id",$req->id)->update(['tanggal_peminjaman'=>$req->tanggal_peminjaman,'tanggal_pengembalian'=>$req->tanggal_pengembalian,
    'pegawai_id'=>$req->pegawai_id]);
    
    return back()->with(['success' => 'Data berhasil diupdate']);

    
  }

  public static function cekemail($username, $id = null)
  {

    $cek = DB::table('user')->where("username", $username)->first();

    if ($cek != null) {
      if ($id != null) {
        if ($cek->id != $id) {
          return false;
        } else {
          return true;
        }
      } else {
        return false;
      }
    } else {
      return true;
    }
  }

  public function deleteDir($dirPath)
  {
    if (!is_dir($dirPath)) {
      return false;
    }
    if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
      $dirPath .= '/';
    }
    $files = glob($dirPath . '*', GLOB_MARK);
    foreach ($files as $file) {
      if (is_dir($file)) {
        self::deleteDir($file);
      } else {
        unlink($file);
      }
    }
    rmdir($dirPath);
  }
}
