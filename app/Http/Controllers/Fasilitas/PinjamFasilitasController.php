<?php

namespace App\Http\Controllers\Fasilitas;
use App\Http\Controllers\Controller;
use App\Http\Controllers\NotifikasiController as Notifikasi;

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
    $userLogin = Auth::user();
    $admin = DB::table('peminjaman_fasilitas_jadwal')->get();
    $byId = DB::table('peminjaman_fasilitas_jadwal')->where("user_id",$userLogin->id)->get();
    $data = $userLogin->role_id == 1 || DB::table("pegawai")->where("user_id",Auth::user()->id)->where("is_tata_usaha","Y" )->get()->isNotEmpty() ? $admin : $byId;

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
          '<a href="pinjam-fasilitas/hapus/'.$data->id.'" class="btn btn-danger btn-lg" title="hapus">' .
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
      ->addColumn('acc', function ($data) {
        if($data->pegawai_id){
          $employee = DB::table("pegawai")->where("id",$data->pegawai_id)->first()->nama_lengkap;
          return $employee;
        }else{
          $pegawai = DB::table("pegawai")->where("user_id",Auth::user()->id)->where("is_tata_usaha","Y")->first();
          if($pegawai){
            return '<a href="' . url('admin/pinjam-fasilitas/acc?&id='.$data->id). '" class="badge badge-warning p-2 badge-lg">ACC Sekarang</a>';
          }else{
            return '<span class="badge badge-warning p-2 badge-lg">PROSES</span>';
          }
        }
      })
      ->rawColumns(['aksi','user','fasilitas','waktu','tanggal','acc'])
      ->addIndexColumn()
      ->make(true);
  }
  public function simpan(Request $req)
  {
      try {
        if($req->pegawai_id){
          DB::table("peminjaman_fasilitas_jadwal")
          ->insert([
            "peminjaman_fasilitas_id" => $req->peminjaman_fasilitas_id,
            "jam_mulai" => $req->jam_mulai,
            "jam_selesai" => $req->jam_selesai,
            "pegawai_id" => $req->pegawai_id,
            "user_id" => $req->user_id,
            "tanggal" => $req->tanggal,
          ]);
        }else{
          $req->user_id = Auth::user()->id; 
          DB::table("peminjaman_fasilitas_jadwal")
          ->insert([
            "peminjaman_fasilitas_id" => $req->peminjaman_fasilitas_id,
            "jam_mulai" => $req->jam_mulai,
            "jam_selesai" => $req->jam_selesai,
            "user_id" => $req->user_id,
            "tanggal" => $req->tanggal,
          ]);
        }


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
        $datas = DB::table("peminjaman_fasilitas_jadwal")
        ->join("user","user.id","=","peminjaman_fasilitas_jadwal.user_id")
        ->join("role","role.id","=","user.role_id")
        ->join("peminjaman_fasilitas","peminjaman_fasilitas.id","=","peminjaman_fasilitas_jadwal.peminjaman_fasilitas_id")
        ->select("peminjaman_fasilitas_jadwal.*","peminjaman_fasilitas.nama as nama_fasilitas","user.role_id","role.nama as role_nama")
        ->whereNotNull('pegawai_id')->get(); // sudah di acc

        foreach($datas as $data){
          if($data->role_id == 1){
            $data->user_nama = "admin";
          } else if($data->role_id == 2) {
              $cekdata = DB::table("siswa")->where('user_id', $data->user_id)->first();
    
              $data->user_nama = $cekdata->nama_lengkap;
          } else if($data->role_id == 3) {
              $cekdata = DB::table("wali_murid")->where('user_id', $data->user_id)->first();
    
              $data->user_nama = $cekdata->nama_lengkap;
          } else if($data->role_id == 4) {
              $cekdata = DB::table("guru")->where('user_id', $data->user_id)->first();
    
              $data->user_nama = $cekdata->nama_lengkap;
          } else if($data->role_id == 5) {
              $cekdata = DB::table("pegawai")->where('user_id', $data->user_id)->first();
    
              $data->user_nama = $cekdata->nama_lengkap;
          } else if($data->role_id == 6) {
              $cekdata = DB::table("kepala_sekolah")->where('user_id', $data->user_id)->first();
    
              $data->user_nama = $cekdata->nama_lengkap;
          } else if($data->role_id == 7) {
              $cekdata = DB::table("dinas_pendidikan")->where('user_id', $data->user_id)->first();
              $data->user_nama = $cekdata->nama_lengkap;
          }
        }
        return response()->json(['status' => 1, 'data'=>$datas]);

      }else{
        $datas = DB::table("peminjaman_fasilitas_jadwal")
        ->join("user","user.id","=","peminjaman_fasilitas_jadwal.user_id")
        ->join("role","role.id","=","user.role_id")
        ->join("peminjaman_fasilitas","peminjaman_fasilitas.id","=","peminjaman_fasilitas_jadwal.peminjaman_fasilitas_id")
        ->select("peminjaman_fasilitas_jadwal.*","peminjaman_fasilitas.nama as nama_fasilitas","user.role_id","role.nama as role_nama")
        ->whereNull('pegawai_id')->get(); // belom di acc
        foreach($datas as $data){
          if($data->role_id == 1){
            $data->user_nama = "admin";
          } else if($data->role_id == 2) {
              $cekdata = DB::table("siswa")->where('user_id', $data->user_id)->first();
    
              $data->user_nama = $cekdata->nama_lengkap;
          } else if($data->role_id == 3) {
              $cekdata = DB::table("wali_murid")->where('user_id', $data->user_id)->first();
    
              $data->user_nama = $cekdata->nama_lengkap;
          } else if($data->role_id == 4) {
              $cekdata = DB::table("guru")->where('user_id', $data->user_id)->first();
    
              $data->user_nama = $cekdata->nama_lengkap;
          } else if($data->role_id == 5) {
              $cekdata = DB::table("pegawai")->where('user_id', $data->user_id)->first();
    
              $data->user_nama = $cekdata->nama_lengkap;
          } else if($data->role_id == 6) {
              $cekdata = DB::table("kepala_sekolah")->where('user_id', $data->user_id)->first();
    
              $data->user_nama = $cekdata->nama_lengkap;
          } else if($data->role_id == 7) {
              $cekdata = DB::table("dinas_pendidikan")->where('user_id', $data->user_id)->first();
              $data->user_nama = $cekdata->nama_lengkap;
          }
        }
        return response()->json(['status' => 1, 'data'=>$datas]);
      }
    } catch (\Exception $e) {
      DB::rollback();
      return response()->json(["status" => 2, "message" => $e->getMessage()]);
    }
  }

  public function APIaccPeminjaman(Request $req){
    try {
      $data = DB::table("peminjaman_fasilitas_jadwal")
      ->where("id",$req->id)
      ->update([
        "pegawai_id" => $req->pegawai_id,
      ]);

      DB::commit();
      if($data){
        $user_id = DB::table("peminjaman_fasilitas_jadwal")
        ->where("id",$req->id)->first()->user_id;
        Notifikasi::push_notifikasi($user_id,"Pinjam Fasilitas","Peminjaman fasilitas berhasil di konfirmasi kamu bisa menggunakan fasilitas sekolah");
        return response()->json(["status" => 1,"message" => "berhasil di ACC"]);
      }else{
      return response()->json(["status" => 2,"message" => "data sudah di acc atau id tidak ditemukan"]);
      }
    } catch (\Exception $e) {
      DB::rollback();
      return response()->json(["status" => 2, "message" => $e->getMessage()]);
    }
  }

  public function accPeminjaman(Request $req){
    try {
      $req->pegawai_id = DB::table("pegawai")->where("user_id",Auth::user()->id)->first()->id;

      DB::table("peminjaman_fasilitas_jadwal")
      ->where("id",$req->id)
      ->update([
        "pegawai_id" => $req->pegawai_id,
      ]);

      DB::commit();
        $user_id = DB::table("peminjaman_fasilitas_jadwal")
        ->where("id",$req->id)->first()->user_id;
        Notifikasi::push_notifikasi($user_id,"Pinjam Fasilitas","Peminjaman fasilitas berhasil di konfirmasi kamu bisa menggunakan fasilitas sekolah");
        return back()->with(['success' => 'berhasil di ACC']);
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
      ->where('id',$id);
      Notifikasi::push_notifikasi($data->first()->user_id,"Gagal Pinjam Fasilitas","Peminjaman fasilitas ditolak pegawai, mungkin fasilitas sedang dipakai");
      $data->delete();
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
    return back()->with(['success' => 'Data berhasil diubah']);
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
