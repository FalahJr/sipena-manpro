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

class JadwalPembelajaranController extends Controller
{
  public function index()
  {
    $mapel = DB::table("mapel")->get();
    $kelas = DB::table("kelas")->get();

    //  $alltoko = DB::table("user")->where("is_login", 'Y')->count();

    return view("jadwal-pembelajaran.index", compact('mapel', 'kelas' ));
    // return view('kelas.index');
  }

  public function datatable()
  {
    $data = DB::table('jadwal_pembelajaran')
      ->get();
      

    // return $data;
    // $xyzab = collect($data);
    // return $xyzab;
    // return $xyzab->i_price;
    return Datatables::of($data)
          ->addColumn("mapel", function ($data) {
            $mapel = DB::table('mapel')->where('id', $data->mapel_id)->first();
            
            return $mapel->nama ;
          })
          ->addColumn("kelas", function ($data) {
            $kelas = DB::table('kelas')->where('id', $data->kelas_id)->first();
            
            return $kelas->nama ;
          })
      ->addColumn('aksi', function ($data) {
        return '<div class="btn-group">' .
          '<a href="jadwal-pembelajaran/edit/' . $data->id . '" class="btn btn-info btn-lg">' .
          '<label class="fa fa-pencil-alt"></label></a>' .
          '<a href="jadwal-pembelajaran/hapus/' . $data->id . '" class="btn btn-danger btn-lg" title="hapus">' .
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
    $max = DB::table("jadwal_pembelajaran")->max('id') + 1;

    if ($req->id == null) {
      if (!$this->cekemail($req->mapel_id, $req->kelas_id)) {
        return response()->json(["status" => 7, "message" => "Jadwal sudah ada, tidak dapat disimpan!"]);
      }
      DB::beginTransaction();

      try {
        $tes = DB::table("jadwal_pembelajaran")
          ->insert([
            "id" => $max,
            "mapel_id" => $req->mapel_id,
            "kelas_id" => $req->kelas_id,
            "jadwal_hari" => $req->jadwal_hari,
            "jadwal_waktu" => $req->jadwal_waktu,
            "created_at" => Carbon::now('Asia/Jakarta'),
          ]);
        DB::commit();
        return response()->json(["status" => 1]);
      } catch (\Exception $e) {
        DB::rollback();
        return response()->json(["status" => 2]);
      }
    } 
    // else {
    //   if (!$this->cekemail($req->mapel_id,$req->kelas_id, $req->id)) {
    //     return response()->json(["status" => 7, "message" => "Data Jadwal sudah ada, tidak dapat disimpan!"]);
    //   }
    //   DB::beginTransaction();
    //   try {
       
    //     $tes = DB::table("jadwal_pembelajaran")
    //       ->where('id', $req->id)
    //       ->update([
    //         "id" => $max,
    //         "mapel_id" => $req->nama,
    //         "kelas_id" => $req->walikelas,
    //         "jadwal_hari" => $req->jadwal_hari,
    //         "jadwal_waktu" => $req->jadwal_waktu,
    //         "created_at" => Carbon::now('Asia/Jakarta'),
    //       ]);

          
          
    //     DB::commit();
    //     return response()->json(["status" => 3]);
    //   } catch (\Exception $e) {
    //     DB::rollback();
    //     return response()->json(["status" => 4]);
    //   }
    // }
  }

  public function hapus($id)
  {
    

    DB::table("jadwal_pembelajaran")
      ->where('id', $id)
      ->delete();

    return back()->with(['success' => 'Data berhasil dihapus']);
  }

  public function edit($id)
  {
    $detail = DB::table("jadwal_pembelajaran")->where("id", $id)->first();
    $mapelEdit= DB::table("mapel")->where('id', $detail->mapel_id)->first();
    $kelas = DB::table("kelas")->where('id', $detail->kelas_id)->first();

    // dd($data);
    return view("jadwal-pembelajaran.edit", compact('detail', 'mapelEdit','kelas'));

  }

  public function update(Request $request)
  {
    $this->validate($request, [
      'jadwal_hari' => 'required|max:100',
      'jadwal_waktu' => 'required',
    ]);

    // $guru_id = DB::table("jadwal_pembelajaran")
    // ->where('id', $request->id)
    // ->first();
    $newData = request()->except(['_token']);
    $data = DB::table("jadwal_pembelajaran")->where('id', $request->id)->update($newData);
    // DB::table('guru')->where('id',$guru_id->guru_id)->update(['is_walikelas'=> "N"]);

    // DB::table('guru')->where('id',$request->guru_id)->update(['is_walikelas'=> "Y"]);
    // dd($data);
    return back()->with(['success' => 'Data berhasil diupdate']);


  }

  public static function cekemail($mapel,$kelas, $id = null)
  {

    $cek = DB::table('jadwal_pembelajaran')->where("mapel_id", $mapel)->where("kelas_id", $kelas)->first();

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