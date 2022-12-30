<?php

namespace App\Http\Controllers;

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

class MenuKantinController extends Controller
{


  public function index()
  {
    return view('menu_kantin.index');
  }

  public function datatable()
  {
    $data = DB::table('kantin_list')->where("kantin_id",  DB::table("kantin")->where("pegawai_id",DB::table("pegawai")->where("user_id",Auth::user()->id)->first()->id)->first()->id)->get();

    return Datatables::of($data)
      ->addColumn('aksi', function ($data) {
        return  '<div class="btn-group">' .
        '<a href="menu-kantin/edit/' . $data->id . '" class="btn btn-info btn-lg">'.
        '<label class="fa fa-pencil-alt"></label></a>' .
        '<a href="menu-kantin/hapus/'.$data->id.'" class="btn btn-danger btn-lg" title="hapus">' .
        '<label class="fa fa-trash"></label></a>';
      })
      ->addColumn('harga', function ($data) {
        return FormatRupiahFront($data->harga);
      })
      ->rawColumns(['aksi'])
      ->addIndexColumn()
      ->make(true);
  }


  public function getData(Request $req){
    try{
      if($req->kantin_id){
        $data = DB::table('kantin_list')->where("kantin_id", $req->kantin_id)->first();
        return response()->json(["status" => 1, "data" => $data]);
      }else{
        $data = DB::table('kantin_list')->where("kantin_id",  DB::table("kantin")->where("pegawai_id",DB::table("pegawai")->where("user_id",Auth::user()->id)->first()->id)->first()->id)->get();
        return response()->json(["status" => 1, "data" => $data]);
      }
    }catch(\Exception $e){
      return response()->json(["status" => 2, "message" => $e->getMessage()]);
    }
  }

  public function simpan(Request $req)
  {
      try {
        DB::table("kantin_list")
        ->insert([
          "kantin_id" => DB::table("kantin")->where("pegawai_id",DB::table("pegawai")->where("user_id",Auth::user()->id)->first()->id)->first()->id,
          "nama" => $req->nama,
          "harga" => $req->harga,
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
    DB::table("kantin_list")
    ->where('id',$id)
    ->delete();

      return back()->with(['success' => 'Data berhasil dihapus']);
  }

  public function edit($id)
  {
    $data = DB::table("kantin_list")->where("id", $id)->first();
    return view("menu_kantin.edit", compact('data'));
  }

  public function update(Request $req)
  {
    $this->validate($req,[
      'nama' => 'required|max:255',
      'harga' => 'required|max:255',
    ]);

    DB::table("kantin_list")->where('id',$req->id)->update(['nama'=>$req->nama,'harga'=>$req->harga]);
    
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
