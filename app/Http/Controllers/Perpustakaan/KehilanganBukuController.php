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

class KehilanganBukuController extends Controller
{
  public function index()
  {
    return view('kehilangan_buku.index');
  }

  public function datatable()
  {
    $data = DB::table('perpus_kehilangan')
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
          '<a href="kehilangan-buku/edit/' . $data->id . '" class="btn btn-info btn-lg">'.
          '<label class="fa fa-pencil-alt"></label></a>' .
          '<a href="/admin/kehilangan-buku/hapus/'.$data->id.'" class="btn btn-danger btn-lg" title="hapus">' .
          '<label class="fa fa-trash"></label></a>' .
          '</div>';
      })->addColumn('buku', function ($data) {
        $katalog = DB::table("perpus_katalog")->where("id", $data->perpus_katalog_id)->first();
        $urlBook = '<a href="javascript:void(0)" data-id="'.$katalog->id.'" class="showBook" title="show">' . $katalog->judul .'</a><br>';
        return $urlBook;
      })
      ->addColumn('user', function ($data) {
        $user = DB::table("user")->where("id", $data->user_id)->first();
        return $user->username;
      })
      ->rawColumns(['aksi', 'buku','user'])
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

        DB::table("perpus_kehilangan")
          ->insert([
            "perpus_katalog_id" => $req->perpus_katalog_id,
            "nominal" => $req->nominal,
            "tanggal_pembayaran" => $req->tanggal_pembayaran,
          ]);
          DB::commit();

        return response()->json(["status" => 1]);
      } catch (\Exception $e) {
        DB::rollback();
        return response()->json(["status" => 7, "message" => "aman"]);
      }
  }

  public function hapus($id)
  {
    DB::table("perpus_kehilangan")
        ->where('id',$id)
        ->delete();

      return back()->with(['success' => 'Data berhasil dihapus']);
  }

  public function edit($id)
  {
    $data = DB::table("perpus_kehilangan")->where("id", $id)->first();
    
    $items = DB::table("perpus_katalog")->get();
    $book_id = DB::table("perpus_katalog")->where("id",$data->perpus_katalog_id)->first()->id;
    // dd($data);
    return view("kehilangan_buku.edit", compact('data','book_id','items'));
    
  }

  public function update(Request $req)
  {
    $this->validate($req,[
      'perpus_katalog_id' => 'required|max:255',
      'tanggal_pembayaran' => 'required|max:255',
    ]);
    DB::table("perpus_kehilangan")->where("id",$req->id)->update(['perpus_katalog_id'=>$req->perpus_katalog_id,'tanggal_pembayaran'=>$req->tanggal_pembayaran]);
    
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
