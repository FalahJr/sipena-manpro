<?php

namespace App\Http\Controllers;

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

class KelasController extends Controller
{
  public function index()
  {
    $guru = DB::table("guru")->where('is_walikelas', "N")->get();

    //  $alltoko = DB::table("user")->where("is_login", 'Y')->count();

    return view("kelas.index", compact('guru' ));
    // return view('kelas.index');
  }

  public function datatable()
  {
    $data = DB::table('kelas')
      ->get();
      

    // return $data;
    // $xyzab = collect($data);
    // return $xyzab;
    // return $xyzab->i_price;
    return Datatables::of($data)
          ->addColumn("guru", function ($data) {
            $guru = DB::table('guru')->where('id', $data->guru_id)->first();
            
            return $guru->nama_lengkap ;
          })
      ->addColumn('aksi', function ($data) {
        return '<div class="btn-group">' .
          '<a href="kelas/edit/' . $data->id . '" class="btn btn-info btn-lg">' .
          '<label class="fa fa-pencil-alt"></label></a>' .
          '<a href="kelas/hapus/' . $data->id . '" class="btn btn-danger btn-lg" title="hapus">' .
          '<label class="fa fa-trash"></label></a>' .
          '</div>';
      })
      ->rawColumns(['aksi', 'image'])
      ->addIndexColumn()
      ->make(true);
  }

  public function simpan(Request $req)
  {
    // dd(;
    $max = DB::table("kelas")->max('id') + 1;

    if ($req->id == null) {
      if (!$this->cekemail($req->nama)) {
        return response()->json(["status" => 7, "message" => "Kelas sudah ada, tidak dapat disimpan!"]);
      }
      DB::beginTransaction();

      try {
        $tes = DB::table("kelas")
          ->insert([
            "id" => $max,
            "nama" => $req->nama,
            "guru_id" => $req->walikelas,
            "created_at" => Carbon::now('Asia/Jakarta'),
          ]);
          DB::table('guru')->where('id',$req->walikelas)->update(['is_walikelas'=> "Y"]);
        DB::commit();
        return response()->json(["status" => 1]);
      } catch (\Exception $e) {
        DB::rollback();
        return response()->json(["status" => 2]);
      }
    } else {
      if (!$this->cekemail($req->nama, $req->id)) {
        return response()->json(["status" => 7, "message" => "Data Kelas sudah ada, tidak dapat disimpan!"]);
      }
      DB::beginTransaction();
      try {
       
        $tes = DB::table("kelas")
          ->where('id', $req->id)
          ->update([
            "id" => $max,
            "nama" => $req->nama,
            "guru_id" => $req->walikelas,
            "created_at" => Carbon::now('Asia/Jakarta'),
          ]);

          
          
        DB::commit();
        return response()->json(["status" => 3]);
      } catch (\Exception $e) {
        DB::rollback();
        return response()->json(["status" => 4]);
      }
    }
  }

  public static function getData(){
    try{
      $data = DB::table("kelas")->get();
      return response()->json(['status' => 1, 'data'=>$data]);
    } catch (\Exception $e) {
      DB::rollback();
      return response()->json(["status" => 2, "message" => $e->getMessage()]);
    }
  }

  public function hapus($id)
  {
    $guru_id = DB::table("kelas")
      ->where('id', $id)
      ->first();

    DB::table("guru")
      ->where('id', $guru_id->guru_id)
      ->update(['is_walikelas'=> "N"]);

    DB::table("kelas")
      ->where('id', $id)
      ->delete();

    return back()->with(['success' => 'Data berhasil dihapus']);
  }

  public function edit($id)
  {
    $data = DB::table("kelas")->where("id", $id)->first();
    $guru = DB::table("guru")->where('is_walikelas', "N")->get();

    // dd($data);
    return view("kelas.edit", compact('data', 'guru'));

  }

  public function update(Request $request)
  {
    $this->validate($request, [
      'nama' => 'required|max:100',
      'guru_id' => 'max:2',
    ]);

    $guru_id = DB::table("kelas")
    ->where('id', $request->id)
    ->first();
    $newData = request()->except(['_token']);
    $data = DB::table("kelas")->where('id', $request->id)->update($newData);
    DB::table('guru')->where('id',$guru_id->guru_id)->update(['is_walikelas'=> "N"]);

    DB::table('guru')->where('id',$request->guru_id)->update(['is_walikelas'=> "Y"]);
    // dd($data);
    return back()->with(['success' => 'Data berhasil diupdate']);


  }

  public static function cekemail($nama, $id = null)
  {

    $cek = DB::table('kelas')->where("nama", $nama)->first();

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