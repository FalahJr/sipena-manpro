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

class PinjamFasilitasController extends Controller
{
  public function index()
  {
    $employees = DB::table("pegawai")->where("is_tata_usaha","Y")->get();
    $facilities = DB::table("peminjaman_fasilitas")->get();
    $users = DB::table("user")->get();
    return view('pinjam_fasilitas.index',compact('employees','users','facilities'));
  }

  public function datatable()
  {
    $data = DB::table('peminjaman_fasilitas_jadwal')->get();

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
          '<a href="pinjam-fasilitas/edit/' . $data->id . '" class="btn btn-info btn-lg">'.
          '<label class="fa fa-pencil-alt"></label></a>' .
          '<a href="/admin/pinjam-fasilitas/hapus/'.$data->id.'" class="btn btn-danger btn-lg" title="hapus">' .
          '<label class="fa fa-trash"></label></a>';
      })
      ->addColumn('user', function ($data) {
        $user = DB::table("user")->where("id",$data->user_id)->first();
        return $user->username;
      })
      ->addColumn('fasilitas', function ($data) {
        $facility = DB::table("peminjaman_fasilitas")->where("id",$data->peminjaman_fasilitas_id)->first();
        return $facility->nama;
      })
      ->addColumn('waktu', function ($data) {
        return $data->jam_mulai ." - ".$data->jam_selesai;
      })
      ->addColumn('tanggal', function ($data) {
        return Carbon::CreateFromFormat('Y-m-d',$data->tanggal)->format('d M Y');
      })
      ->addColumn('pegawai', function ($data) {
        $employee = DB::table("pegawai")->where("id",$data->pegawai_id)->first();
        return $employee->nama_lengkap;
      })
      ->rawColumns(['aksi','user','fasilitas','waktu','tanggal','pegawai'])
      ->addIndexColumn()
      ->make(true);
  }
  public function simpan(Request $req)
  {
      try {
        DB::table("peminjaman_fasilitas_jadwal")
        ->insert([
          "peminjaman_fasilitas_id" => $req->peminjaman_fasilitas_id,
          "jam_mulai" => $req->jam_mulai,
          "jam_selesai" => $req->jam_selesai,
          "pegawai_id" => $req->pegawai_id,
          "user_id" => $req->user_id,
          "tanggal" => Carbon::now('Asia/Jakarta'),
        ]);

          DB::commit();

        return response()->json(["status" => 1]);
      } catch (\Exception $e) {
        DB::rollback();
        return response()->json(["status" => 7, "message" => $e]);
      }
  }

  public function ajukanPeminjaman(Request $req){
    try {
      DB::table("peminjaman_fasilitas_jadwal")
      ->insert([
        "peminjaman_fasilitas_id" => $req->peminjaman_fasilitas_id,
        "jam_mulai" => $req->jam_mulai,
        "jam_selesai" => $req->jam_selesai,
        "user_id" => $req->user_id,
        "tanggal" => $req->tanggal,
      ]);

        DB::commit();

      return response()->json(["status" => 1,"message" => "berhasil diajukan"]);
    } catch (\Exception $e) {
      DB::rollback();
      return response()->json(["status" => 2, "message" => $e->getMessage()]);
    }
  }

  public function getData(Request $req){
    try{
      if($req->acc){
        $data = DB::table("peminjaman_fasilitas_jadwal")->whereNotNull('pegawai_id')->get(); // sudah di acc
      }else{
        $data = DB::table("peminjaman_fasilitas_jadwal")->whereNull('pegawai_id')->get(); // belom di acc
      }
      return response()->json(['status' => 1, 'data'=>$data]);
    } catch (\Exception $e) {
      DB::rollback();
      return response()->json(["status" => 2, "message" => $e->getMessage()]);
    }
  }

  public function accPeminjaman(Request $req){
    try {
      $data = DB::table("peminjaman_fasilitas_jadwal")
      ->where("id",$req->id)
      ->update([
        "pegawai_id" => $req->pegawai_id,
      ]);

        DB::commit();
      if($data)
      return response()->json(["status" => 1,"message" => "berhasil di ACC"]);
      else
      return response()->json(["status" => 2,"message" => "data sudah di acc atau id tidak ditemukan"]);
    } catch (\Exception $e) {
      DB::rollback();
      return response()->json(["status" => 2, "message" => $e->getMessage()]);
    }
  }

  public function hapus($id)
  {
    DB::table("peminjaman_fasilitas_jadwal")
        ->where('id',$id)
        ->delete();

      return back()->with(['success' => 'Data berhasil dihapus']);
  }

  public function edit($id)
  {
    $data = DB::table("peminjaman_fasilitas_jadwal")->where("id",$id)->first();
    $employees = DB::table("pegawai")->where("is_tata_usaha","Y")->get();
    $facilities = DB::table("peminjaman_fasilitas")->get();
    $users = DB::table("user")->get();
    return view("pinjam_fasilitas.edit", compact('data','employees','facilities','users'));
  }
  public function delete($id){
    if($id){
      $data = DB::table("peminjaman_fasilitas_jadwal")
      ->where('id',$id)
      ->delete();
      if($data){
        return response()->json(["status" => 1,"message"=>"data berhasil dihapus"]);
      }else{
        return response()->json(["status" => 2,"message"=>"data tidak ditemukan"]);
      }
    }
    return response()->json(["status" => 2,"message"=>"masukkan url id"]);
  }
  public function update(Request $req)
  {
    $this->validate($req,[
      'peminjaman_fasilitas_id' => 'required|max:150',
      'jam_mulai' => 'required|max:100',
      'jam_selesai' => 'required|max:255',
      'pegawai_id' => 'required|max:11',
      'user_id' => 'required|max:11',
    ]);

    $data = DB::table("peminjaman_fasilitas_jadwal")->where('id',$req->id);
    $data->update(['peminjaman_fasilitas_id'=>$req->peminjaman_fasilitas_id,'jam_mulai'=>$req->jam_mulai,'jam_selesai'=>$req->jam_selesai,'pegawai_id'=>$req->pegawai_id,'user_id'=>$req->user_id]);
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
