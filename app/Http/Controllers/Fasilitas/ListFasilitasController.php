<?php

namespace App\Http\Controllers\Fasilitas;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Account;

use App\Authentication;

use Auth;


use SimpleSoftwareIO\QrCode\Facades\QrCode;


use Carbon\Carbon;

use Session;

use DB;

use File;

use Yajra\Datatables\Datatables;

use Response;

class ListFasilitasController extends Controller
{
  public function index()
  {
    return view('list_fasilitas.index');
  }

  public function datatable()
  {
    $data = DB::table('peminjaman_fasilitas')->get();


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
          '<a href="list-fasilitas/edit/' . $data->id . '" class="btn btn-info btn-lg">'.
          '<label class="fa fa-pencil-alt"></label></a>' .
          '<a href="/admin/list-fasilitas/hapus/'.$data->id.'" class="btn btn-danger btn-lg" title="hapus">' .
          '<label class="fa fa-trash"></label></a>';
      })
      ->rawColumns(['aksi'])
      ->addIndexColumn()
      ->make(true);
  }

  public function simpan(Request $req)
  {
      try {
      
        DB::table("peminjaman_fasilitas")
        ->insert([
          "nama" => $req->nama,
        ]);

          DB::commit();

        return response()->json(["status" => 1]);
      } catch (\Exception $e) {
        DB::rollback();
        return response()->json(["status" => 7, "message" => $e]);
      }
  }

  public function hapus($id)
  {
    DB::table("peminjaman_fasilitas")
        ->where('id',$id)
        ->delete();

      return back()->with(['success' => 'Data berhasil dihapus']);
  }

  public function edit($id)
  {
    $data = DB::table("peminjaman_fasilitas")->where("id", $id)->first();
    return view("list_fasilitas.edit", compact('data'));
  }

  public function update(Request $req)
  {
    $this->validate($req,[
      'nama' => 'required|max:255',
    ]);

    $data = DB::table("peminjaman_fasilitas")->where('id',$req->id);
    $data->update(['nama'=>$req->nama]);
    // dd($data);
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
