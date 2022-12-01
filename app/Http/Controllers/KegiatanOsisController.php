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

class KegiatanOsisController extends Controller
{
  public function index()
  {
    $students = DB::table("siswa")->where("is_osis","Y")->get();
    return view('kegiatan_osis.index',compact('students'));
  }

  public function datatable()
  {
    $data = DB::table('kegiatan_osis')->get();

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
          '<a href="kegiatan-osis/edit/' . $data->id . '" class="btn btn-info btn-lg">'.
          '<label class="fa fa-pencil-alt"></label></a>' .
          '<a href="/admin/kegiatan-osis/hapus/'.$data->id.'" class="btn btn-danger btn-lg" title="hapus">' .
          '<label class="fa fa-trash"></label></a>';
      })
      ->addColumn('waktu', function ($data) {
        return $data->jam_mulai ." - ".$data->jam_selesai;
      })
      ->addColumn('tanggal', function ($data) {
        return Carbon::CreateFromFormat('Y-m-d',$data->tanggal)->format('d M Y');
      })
      ->rawColumns(['aksi','waktu','tanggal'])
      ->addIndexColumn()
      ->make(true);
  }
  public function simpan(Request $req)
  {
      try {
        DB::table("kegiatan_osis")
        ->insert([
          "kegiatan" => $req->kegiatan,
          "jam_mulai" => $req->jam_mulai,
          "jam_selesai" => $req->jam_selesai,
          "pelaksana" => $req->pelaksana,
          "tanggal" => Carbon::now('Asia/Jakarta'),
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
    DB::table("kegiatan_osis")
        ->where('id',$id)
        ->delete();

      return back()->with(['success' => 'Data berhasil dihapus']);
  }

  public function edit($id)
  {
    $data = DB::table("kegiatan_osis")->where("id", $id)->first();
    $students = DB::table('siswa')->where("is_osis","Y")->get();
    return view("kegiatan_osis.edit", compact('data','students'));
  }

  public function update(Request $req)
  {
    $this->validate($req,[
      'kegiatan' => 'required|max:150',
      'jam_mulai' => 'required|max:100',
      'jam_selesai' => 'required|max:255',
      'pelaksana' => 'required|max:150',
    ]);

    $data = DB::table("kegiatan_osis")->where('id',$req->id);
    $data->update(['kegiatan'=>$req->kegiatan,'jam_mulai'=>$req->jam_mulai,'jam_selesai'=>$req->jam_selesai,'pelaksana'=>$req->pelaksana]);
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