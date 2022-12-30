<?php

namespace App\Http\Controllers;
use App\Http\Controllers\NotifikasiController as Notifikasi;
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


  public function getSiswaByKelas(Request $req)
  {
    $siswa = DB::table('siswa')
    ->join('kelas','kelas.id','=','siswa.kelas_id')
    ->join('wali_murid','wali_murid.id','=','siswa.wali_murid_id')
    ->join('user','user.id','=','siswa.user_id')
    ->select("siswa.*","kelas.nama as kelas","wali_murid.nama_lengkap as wali_murid","user.is_active")
    ->where("kelas_id", $req->kelas_id)->get();

    return response()->json($siswa);
  }

  public function getSiswaByWalimurid(Request $req)
  {
    $siswa = DB::table('siswa')
    ->join('kelas','kelas.id','=','siswa.kelas_id')
    ->join('wali_murid','wali_murid.id','=','siswa.wali_murid_id')
    ->join('user','user.id','=','siswa.user_id')
    ->select("siswa.*","kelas.nama as kelas","wali_murid.nama_lengkap as wali_murid","user.is_active")
    ->where("wali_murid_id", $req->walimurid_id)->get();

    return response()->json($siswa);
  }

  public function getSiswa()
  {
    $siswa = DB::table('siswa')
    ->join('kelas','kelas.id','=','siswa.kelas_id')
    ->join('wali_murid','wali_murid.id','=','siswa.wali_murid_id')
    ->join('user','user.id','=','siswa.user_id')
    ->select("siswa.*","kelas.nama as kelas","wali_murid.nama_lengkap as wali_murid","user.is_active")
    ->get();

    return response()->json($siswa);
  }

  public function osisindex()
  {
    // $classes = DB::table('kelas')->get();
    // $studentGuardians = DB::table('wali_murid')->get();
    $students = DB::table('siswa')->join("user","user.id","=","siswa.user_id")->select("siswa.*","user.is_active")->where("user.is_active","Y")->whereNull("tanggal_daftar_osis")->get();
    return view('anggota-osis.index',compact('students'));
  }

  public function calonosisindex()
  {
    // $classes = DB::table('kelas')->get();
    // $studentGuardians = DB::table('wali_murid')->get();
    return view('anggota-osis.calon-osis');
  }

  public function ppdbindex()
  {
    $ppdb = DB::table("ppdb")->first();
    return view('siswa.ppdb',compact('ppdb'));
  }
  public function datatable()
  {
    $data = DB::table('siswa')
          ->join('kelas','kelas.id','=','siswa.kelas_id')
          ->join('wali_murid','wali_murid.id','=','siswa.wali_murid_id')
          ->join('user','user.id','=','siswa.user_id')
          ->select("siswa.*","kelas.nama as kelas","wali_murid.nama_lengkap as wali_murid","user.is_active")
          ->where("user.is_active","Y")
          ->get();
    return Datatables::of($data)
      ->addColumn('aksi', function ($data) {
        return  '<div class="btn-group border-0">' .
          '<a href="siswa/edit/' . $data->id . '" class="btn btn-info btn-lg">'.
          '<label class="fa fa-pencil-alt"></label></a>' .
          '<a href="siswa/hapus/'.$data->id.'" class="btn btn-danger btn-lg" title="hapus">' .
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
      ->addColumn('tempat_tanggal_lahir',function($data){
        return $data->tempat_lahir.", ".$data->tanggal_lahir;
      })
      ->addColumn('osis',function($data){
        if($data->is_osis == "Y"){
          return '<div class="text-success badge-lg pt-2">'.
          '<label class="fa fa-check"></label></div>';
        }else{
          return '<div class="text-warning badge-lg pt-2">'.
          '<label class="fa fa-close"></label></div>';
        }
      })
      ->rawColumns(['aksi', 'foto_profil', 'kartu_digital', 'osis'])
      ->addIndexColumn()
      ->make(true);
  }

  public function datatablePpdb()
  {
    $data = DB::table('siswa')
          ->join('wali_murid','wali_murid.id','=','siswa.wali_murid_id')
          ->join('user','user.id','=','siswa.user_id')
          ->select("siswa.*","wali_murid.nama_lengkap as wali_murid","user.is_active as is_active",)
          ->where("user.is_active","N")
          ->get();
    return Datatables::of($data)
      ->addColumn('aksi', function ($data) {
        return  '<div class="btn-group">' .
          '<a href="acc/' . $data->user_id . '" class="btn btn-success btn-lg pt-2">'.
          '<label class="fa fa-check"></label></a>' .
          '<a href="tolak/'.$data->user_id.'" class="btn btn-danger btn-lg pt-2" title="hapus">' .
          '<label class="fa fa-close"></label></a>' .
          '</div>';
      })
      ->addColumn('foto_profil', function ($data) {
        $url= asset($data->foto_profil);
        return '<img src="' . $url . '" style="height: 80px; width:80px; border-radius: 0px;" class="img-responsive"> </img>';
      })
      ->addColumn('tempat_tanggal_lahir',function($data){
        return $data->tempat_lahir.", ".$data->tanggal_lahir;
      })
      ->rawColumns(['aksi', 'foto_profil'])
      ->addIndexColumn()
      ->make(true);
  }

  public function accPpdb($id)
  {

    $data = DB::table("user")->where('id', $id)->update(['is_active' => "Y"]);
    $siswa = DB::table("siswa")->where("user_id",$id)->first();
    $maxSiswa = DB::table("kelas")->max('id');

    $linkCode = url('/generatekartudigital?id='.$siswa->id);
    $data2 = DB::table("siswa")->where('user_id', $id)->update(['kelas_id' => $maxSiswa, 'kartu_digital' => $linkCode]);

    $user_id = DB::table("wali_murid")->where("id",$siswa->wali_murid_id)->first();

    Notifikasi::push_notifikasi($user_id->user_id,"Selamat, Anak Anda diterima","Admin telah menerima ".$siswa->nama_lengkap." di Sekolah Kami");

    return back()->with(['success' => 'Siswa Berhasil Diterima']);


  }

  public function tolakPpdb($id)
  {

    

      $siswa = DB::table("siswa")->where("user_id",$id)->first();
      $user_id = DB::table("wali_murid")->where("id",$siswa->wali_murid_id)->first();
  
      Notifikasi::push_notifikasi($user_id->user_id,"Maaf, Anak Anda Belum Diterima","Admin tidak menerima ".$siswa->nama_lengkap." di Sekolah Kami");

      DB::table("siswa")
      ->where('user_id', $id)
      ->delete();

    DB::table("user")
      ->where('id', $id)
      ->delete();

    return back()->with(['success' => 'Siswa Berhasil Ditolak']);


  }
public function osisdatatable()
  {
    $data = DB::table('siswa')
          ->join('kelas','kelas.id','=','siswa.kelas_id')
          ->join('wali_murid','wali_murid.id','=','siswa.wali_murid_id')
          ->join('user','user.id','=','siswa.user_id')
          ->select("siswa.*","kelas.nama as kelas","wali_murid.nama_lengkap as wali_murid","user.is_active")
          ->where("user.is_active","Y")
          ->where("siswa.is_osis","Y")
          ->get();
    return Datatables::of($data)
      ->addColumn('foto_profil', function ($data) {
        $url= asset($data->foto_profil);
        return '<img src="' . $url . '" style="height: 80px; width:80px; border-radius: 0px;" class="img-responsive"> </img>';
      })
      ->addColumn('tempat_tanggal_lahir',function($data){
        return $data->tempat_lahir.", ".$data->tanggal_lahir;
      })
      ->addColumn('aksi',function($data){
        if($data->is_osis == "Y"){
          return '<a href="' . url('admin/anggota-osis/keluar/'.$data->id). '" class="badge badge-danger p-2 badge-lg">Keluar dari OSIS</a>';
        }
      })
      ->rawColumns(['foto_profil', 'kartu_digital', 'aksi'])
      ->addIndexColumn()
      ->make(true);
  }
  public function calonosisdatatable()
  {
    $data = DB::table('siswa')
          ->join('kelas','kelas.id','=','siswa.kelas_id')
          ->join('wali_murid','wali_murid.id','=','siswa.wali_murid_id')
          ->join('user','user.id','=','siswa.user_id')
          ->select("siswa.*","kelas.nama as kelas","wali_murid.nama_lengkap as wali_murid","user.is_active")
          ->where("user.is_active","Y")
          ->where("siswa.is_osis","N")
          ->whereNotNull("siswa.tanggal_daftar_osis")
          ->get();
    return Datatables::of($data)
      ->addColumn('foto_profil', function ($data) {
        $url= asset($data->foto_profil);
        return '<img src="' . $url . '" style="height: 80px; width:80px; border-radius: 0px;" class="img-responsive"> </img>';
      })
      ->addColumn('tempat_tanggal_lahir',function($data){
        return $data->tempat_lahir.", ".$data->tanggal_lahir;
      })
      ->addColumn('aksi',function($data){
          return '<a href="' . url('admin/calon-osis/acc/'.$data->id). '" class="badge badge-success p-2 badge-lg">Acc Permintaan</a>';
      })
      ->rawColumns(['foto_profil', 'kartu_digital', 'aksi'])
      ->addIndexColumn()
      ->make(true);
  }
  public function osisKeluar($id){
    DB::table("siswa")->where("id",$id)->update(["is_osis"=>"N","tanggal_daftar_osis"=>null]);
    return back()->with(['success' => 'siswa keluar dari osis']);
  }
  public function accPermintaan($id){
    DB::table("siswa")->where("id",$id)->update(["is_osis"=>"Y","tanggal_daftar_osis"=>date("Y-m-d")]);
    $user_id = DB::table("siswa")->where("id",$id)->first()->user_id;
    Notifikasi::push_notifikasi($user_id,"Berhasil Daftar OSIS","Selamat anda telah menjadi OSIS, sekarang anda dapat melakukan kegiatan osis dan menambahkan kegiatan osis");
    return back()->with(['success' => 'siswa sekarang menjadi osis']);
  }
  public function listPermintaan(){
    $data = DB::table('siswa')
          ->join('kelas','kelas.id','=','siswa.kelas_id')
          ->join('user','user.id','=','siswa.user_id')
          ->select("siswa.*","kelas.nama as kelas","user.is_active")
          ->where("user.is_active","Y")
          ->where("siswa.is_osis","N")
          ->whereNotNull("siswa.tanggal_daftar_osis")
          ->get();
    return response()->json(["status" => 1,"data"=>$data]);
  }
  public function listAnggota(){
    $data = DB::table('siswa')
    ->join('kelas','kelas.id','=','siswa.kelas_id')
    ->join('user','user.id','=','siswa.user_id')
    ->select("siswa.*","kelas.nama as kelas","user.is_active")
    ->where("user.is_active","Y")
    ->where("siswa.is_osis","Y")
    ->get();
    return response()->json(["status" => 1,"data"=>$data]);
  }

  public function ppdb(Request $req){
    $data = DB::table("ppdb")->first();
    if($data){
      if($req->is_active == "Y"){
        $data = DB::table("ppdb")->update(["is_active"=>"Y"]);
      }else{
        $data = DB::table("ppdb")->update(["is_active"=>"N"]);
      }
      return response()->json(["status" => 1,"message"=>"ppdb berhasil diaktifkan"]);
    }else{
      return response()->json(["status" => 2,"message"=>"data ppdb tidak ada"]);
    }
  }

  public function setPpdb(Request $req){
    $data = DB::table("ppdb")->first();
    if($data){
      if($req->is_active == "Y"){
        $data = DB::table("ppdb")->update(["is_active"=>"Y"]);
        return back()->with(['success' => 'ppdb berhasil diaktifkan']);
      }else{
        $data = DB::table("ppdb")->update(["is_active"=>"N"]);
        return back()->with(['success' => 'ppdb berhasil dinonaktifkan']);
      }
    }else{
      return response()->json(['success' =>"data ppdb tidak ada"]);
    }
  }

  public function getPpdb(Request $req){
    try{
      if($req->id){
        $data = DB::table('siswa')
        ->join("user","user.id","siswa.user_id")
        ->select("siswa.*","user.is_active")
        ->where("siswa.id",$req->id)
        ->where("user.is_active","N")
        ->get();
      }else{
        $data = DB::table('siswa')
        ->join("user","user.id","siswa.user_id")
        ->select("siswa.*","user.is_active")
        ->where("user.is_active","N")
        ->get();
      }

      return response()->json(["status" => 1,"data"=>$data]);
    }catch(\Exception $e){
      return response()->json(["status" => 2,"message"=>$e->getMessage()]);
    }
  }

  public function APIAccPermintaan(Request $req){
    if($req->id){
    $siswa= DB::table("siswa")->where("id",$req->id)->update(["is_osis"=>"Y","tanggal_daftar_osis"=>date("Y-m-d")]);
    if($siswa){
      $siswa= DB::table("siswa")->where("id",$req->id)->first();
      Notifikasi::push_notifikasi($siswa->user_id,"Berhasil Daftar OSIS","Selamat anda telah menjadi OSIS, sekarang anda dapat melakukan kegiatan osis dan menambahkan kegiatan osis");
      return response()->json(["status" => 1,"message"=>"berhasil menjadi anggota osis"]);
      }else{
      return response()->json(["status" => 2,"message"=>"id siswa tidak ditemukan"]);
      }
    }else{
      return response()->json(["status" => 2,"message"=>"masukkan id siswa"]);
    }
  }
  public function APIDaftarOsis(Request $req){
    if($req->id){
      $siswa = DB::table("siswa")->where("id",$req->id)->update(["tanggal_daftar_osis"=>date("Y-m-d")]);
      if($siswa){
      return response()->json(["status" => 1,"message"=>"berhasil mendaftar osis, tunggu acc"]);
      }else{
      return response()->json(["status" => 2,"message"=>"id siswa tidak ditemukan"]);
      }
    }else{
      return response()->json(["status" => 2,"message"=>"masukkan id siswa"]);
    }
  }

  public function daftarOsis(Request $req){
    if($req->id){
      $siswa = DB::table("siswa")->where("id",$req->id)->update(["tanggal_daftar_osis"=>date("Y-m-d")]);

    return back()->with(['success' => 'berhasil mendaftar osis, tunggu acc']);
    }else{

    return back()->with(['success' => 'Gagal']);
    }
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
        $dir = 'image/uploads/Murid/' . $max;
        $childPath = $dir . '/';
        $path = $childPath;

        $file = $req->file('image');
        $name = null;
        if ($file != null) {
          $this->deleteDir($dir);
          $name = $folder . '.' . $file->getClientOriginalExtension();
          if (!File::exists($path)) {
            if (File::makeDirectory($path, 0777, true)) {
              if ($_FILES['image']['type'] == 'image/webp' || $_FILES['image']['type'] == 'image/jpeg' || $_FILES['image']['type'] == 'image/png') {
              } else if ($_FILES['image']['type'] == 'webp' || $_FILES['image']['type'] == 'jpeg' || $_FILES['image']['type'] == 'image/png') {
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
            "nisn" => $req->nisn,
            // "email" => $req->email,
            "tempat_lahir" => $req->tempat_lahir,
            "tanggal_lahir" => $req->tanggal_lahir,
            "nama_ayah" => $req->nama_ayah,
            "nama_ibu" => $req->nama_ibu,
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

  public function tambahAnggotaOsis(Request $req){
    $siswa = DB::table("siswa")->where("id",$req->id)->update(["tanggal_daftar_osis"=>date("Y-m-d"),"is_osis"=>"Y"]);
    return response()->json(["status" => 1]);
  }

  public function hapus($id)
  {
    $siswa = DB::table("siswa")
    ->where('id',$id)
    ->first();

    DB::table("user")
        ->where('id',$siswa->user_id)
        ->update(["is_active"=>"N"]);

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

  public function update(Request $req)
  {
    $this->validate($req,[
      'nama_lengkap' => 'required|max:100',
      'tanggal_lahir' => 'required|max:14',
      'jenis_kelamin' => 'required|max:1',
      'alamat' => 'required|max:100',
      'agama' => 'required|max:100',
      'phone' => 'required|max:100',
      'tanggal_daftar' => 'required|max:100',
      'nama_ayah' => 'required|max:100',
      'nama_ibu' => 'required|max:100',
      'tempat_lahir' => 'required|max:100',
      'nisn' => 'required|max:100',
    ]);
    $imgPath = null;
    $tgl = Carbon::now('Asia/Jakarta');
    $folder = $tgl->year . $tgl->month . $tgl->timestamp;
    $dir = 'image/uploads/User/' . $req->id;
    $childPath = $dir . '/';
    $path = $childPath;

    $file = $req->file('image');
    $name = null;
    $newData = request()->except(['_token','image']);
    if ($file != null) {
      $this->deleteDir($dir);
      $name = $folder . '.' . $file->getClientOriginalExtension();
      if (!File::exists($path)) {
        if (File::makeDirectory($path, 0777, true)) {
          if ($_FILES['image']['type'] == 'image/webp' || $_FILES['image']['type'] == 'image/jpeg' || $_FILES['image']['type'] == 'image/png') {
          } else if ($_FILES['image']['type'] == 'webp' || $_FILES['image']['type'] == 'jpeg' || $_FILES['image']['type'] == 'image/png') {
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
      $newData += ["foto_profil"=>$imgPath];
      DB::table("siswa")->where('id',$req->id)->update($newData);
    }else{
      DB::table("siswa")->where('id',$req->id)->update($newData);
     }

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

  public function updateProfileUser(Request $req){
    try{
      if (!$this->cekemail($req->username)) {
        return response()->json(["status" => 2, "message" => "Data username sudah digunakan, tidak dapat disimpan!"]);
      }
      DB::table("user")->where("id",$req->id)->update([
        "username"=>$req->username,
        "password"=>$req->password,
      ]);
      return response()->json(["status" => 1,"message" => "username atau password berhasil diubah"]);
    }catch(\Exception $e){
      return response()->json(["status" => 2,"message" => $e->getMessage()]);
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
