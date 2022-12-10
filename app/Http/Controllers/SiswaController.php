<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Account;

use App\Authentication;

use Auth;

use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Session;

use DB;

use File;

use Yajra\Datatables\Datatables;

use Response;

class SiswaController extends Controller
{
  public function index()
  {
    $classes = DB::table('kelas')->get();
    $studentGuardians = DB::table('wali_murid')->get(); 
    return view('siswa.index', compact('classes','studentGuardians'));
  }

  public function datatable()
  {
    $data = DB::table('siswa')
          ->join('kelas','kelas.id','=','siswa.kelas_id')
          ->join('wali_murid','wali_murid.id','=','siswa.wali_murid_id')
          ->select("siswa.*","kelas.nama as kelas","wali_murid.nama_lengkap as wali_murid")
          ->get();
    return Datatables::of($data)
      ->addColumn('aksi', function ($data) {
        return  '<div class="btn-group">' .
          '<a href="siswa/edit/' . $data->id . '" class="btn btn-info btn-lg">'.
          '<label class="fa fa-pencil-alt"></label></a>' .
          '<a href="/admin/siswa/hapus/'.$data->id.'" class="btn btn-danger btn-lg" title="hapus">' .
          '<label class="fa fa-trash"></label></a>' .
          '</div>';
      })
      ->addColumn('foto_profil', function ($data) {
        $url= asset($data->foto_profil);
        return '<img src="' . $url . '" style="height: 80px; width:80px; border-radius: 0px;" class="img-responsive"> </img>';
      })
      ->addColumn('kartu_digital',function($data){
        $generateQRCode = QrCode::size(100)->generate($data->kartu_digital);
        return $generateQRCode;
      })
      ->addColumn('osis',function($data){
        if($data->is_osis == "Y"){
          return '<div class="btn-group">' .
          '<div class="btn btn-success btn-lg">'.
          '<label class="fa fa-check"></label></div>' .
          '</div>';
        }else{
          return '<div class="btn-group">' .
          '<div class="btn btn-warning btn-lg">'.
          '<label class="fa fa-close"></label></div>' .
          '</div>';
        }
      })
      ->rawColumns(['aksi', 'foto_profil', 'kartu_digital', 'osis'])
      ->addIndexColumn()
      ->make(true);
  }


  public function simpan(Request $req)
  {
      if (!$this->cekemail($req->username)) {
        return response()->json(["status" => 7, "message" => "Data username sudah digunakan, tidak dapat disimpan!"]);
      }
      DB::beginTransaction();
      try {
        $max = DB::table("user")->max('id') + 1;
        $maxSiswa = DB::table("siswa")->max('id') + 1;

        $imgPath = null;
        $tgl = Carbon::now('Asia/Jakarta');
        $folder = $tgl->year . $tgl->month . $tgl->timestamp;
        $dir = 'image/uploads/User/' . $max;
        $childPath = $dir . '/';
        $path = $childPath;

        $file = $req->file('image');
        $name = null;
        if ($file != null) {
          $this->deleteDir($dir);
          $name = $folder . '.' . $file->getClientOriginalExtension();
          if (!File::exists($path)) {
            if (File::makeDirectory($path, 0777, true)) {
              if ($_FILES['image']['type'] == 'image/webp' || $_FILES['image']['type'] == 'image/jpeg') {
              } else if ($_FILES['image']['type'] == 'webp' || $_FILES['image']['type'] == 'jpeg') {
              } else {
                compressImage($_FILES['image']['type'], $_FILES['image']['tmp_name'], $_FILES['image']['tmp_name'], 75);
              }
              $file->move($path, $name);
              $imgPath = $childPath . $name;
            } else
              $imgPath = null;
          } else {
            return 'already exist';
          }
        }

      $tes=DB::table("user")
          ->insert([
            "id" => $max,
            "username" => $req->username,
            "password" => $req->password,
            "role_id" => 2,
            "is_active" => 'Y',
            "saldo" => 0,
            "created_at" => Carbon::now('Asia/Jakarta'),
          ]);
          $linkCode = url('/generatekartudigital?id='.$maxSiswa);
        
          DB::table("siswa")->insert([
            "id"=>$maxSiswa,
            "user_id" => $max,
            "wali_murid_id" => $req->wali_murid_id,
            "kelas_id" => $req->kelas_id,
            "nama_lengkap" => $req->nama_lengkap,
            "tanggal_lahir" => $req->tanggal_lahir,
            "jenis_kelamin" => $req->jenis_kelamin,
            "alamat" => $req->alamat,
            "agama" => $req->agama,
            "phone" => $req->phone,
            "foto_profil" => $imgPath,
            "kartu_digital" => $linkCode,
            "is_osis" => 'N',
            "tanggal_daftar" => $req->tanggal_daftar,
          ]);
        
          DB::commit();

        

        // }
        return response()->json(["status" => 1]);
      } catch (\Exception $e) {
        DB::rollback();
        return response()->json(["status" => 2,"message"=>$e]);
      }
  }


  public function hapus($id)
  {
    $user_id = DB::table("siswa")
    ->where('id',$id)
    ->first();

    DB::table("siswa")
        ->where('id',$id)
        ->delete();

    DB::table("user")
        ->where('id',$user_id->user_id)
        ->delete();

      return back()->with(['success' => 'Data berhasil dihapus']);
  }

  public function edit($id)
  {
    $classes = DB::table('kelas')->get();
    $studentGuardians = DB::table('wali_murid')->get(); 
    $data = DB::table("siswa")->where("id", $id)->first();
    // dd($data);
    return view("siswa.edit", compact('data','classes','studentGuardians'));

  }

  public function update(Request $request)
  {
    $this->validate($request,[
      'nama_lengkap' => 'required|max:100',
      'tanggal_lahir' => 'required|max:14',
      'jenis_kelamin' => 'required|max:1',
      'alamat' => 'required|max:100',
      'agama' => 'required|max:100',
      'phone' => 'required|max:100',
      'foto_profile' => 'required|max:200',
      'kartu_digital' => 'required|max:100',
      'is_osis' => 'required|max:100',
      'tanggal_daftar' => 'required|max:100',
    ]);
    $newData = request()->except(['_token','image']);
    $data = DB::table("guru")->where('id',$request->id)->update($newData);

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
