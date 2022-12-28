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

class NilaiPembelajaranController extends Controller
{
  public function index()
  {
    $students = DB::table('siswa')->get();
    $lessons = DB::table('mapel')->get();
    $classes = DB::table('kelas')->get();
    return view('nilai_pembelajaran.index',compact('students','lessons','classes'));
  }

  public function datatable()
  {
    if(Auth::user()->role_id == 1){
      $data = DB::table("nilai_pembelajaran")
      ->join("siswa", "siswa.id", '=', 'nilai_pembelajaran.siswa_id')
      ->join("mapel", "mapel.id", '=', 'nilai_pembelajaran.mapel_id')
      ->join("kelas", "kelas.id", '=', 'nilai_pembelajaran.kelas_id')
      ->select("nilai_pembelajaran.*", "siswa.nama_lengkap as nama_siswa","mapel.nama as nama_mapel","kelas.nama as nama_kelas")
      ->get();
    }else if(DB::table("guru")->where("user_id",Auth::user()->id)->where("is_mapel","Y")->get()->isNotEmpty()){
      $mapel =  DB::table("mapel")->where("guru_id",DB::table("guru")->where("user_id",Auth::user()->id)->first()->id)->first();
      if($mapel){
      $data = DB::table("nilai_pembelajaran")
      ->join("siswa", "siswa.id", '=', 'nilai_pembelajaran.siswa_id')
      ->join("mapel", "mapel.id", '=', 'nilai_pembelajaran.mapel_id')
      ->join("kelas", "kelas.id", '=', 'nilai_pembelajaran.kelas_id')
      ->select("nilai_pembelajaran.*", "siswa.nama_lengkap as nama_siswa","mapel.nama as nama_mapel","kelas.nama as nama_kelas")
      ->where("nilai_pembelajaran.mapel_id",$mapel->id)
      ->get();
      }else{
        $data = [];
      }
    }else if(DB::table("guru")->where("user_id",Auth::user()->id)->where("is_walikelas","Y")->get()->isNotEmpty()){
      $kelas =  DB::table("kelas")->where("guru_id",DB::table("guru")->where("user_id",Auth::user()->id)->first()->id)->first();
    if($kelas){
      $data = DB::table("nilai_pembelajaran")
      ->join("siswa", "siswa.id", '=', 'nilai_pembelajaran.siswa_id')
      ->join("mapel", "mapel.id", '=', 'nilai_pembelajaran.mapel_id')
      ->join("kelas", "kelas.id", '=', 'nilai_pembelajaran.kelas_id')
      ->select("nilai_pembelajaran.*", "siswa.nama_lengkap as nama_siswa","mapel.nama as nama_mapel","kelas.nama as nama_kelas")
      ->where("nilai_pembelajaran.kelas_id",$kelas->id)
      ->get();
    }else{
      $data = [];
    }
    }else if(DB::table("guru")->where("user_id",Auth::user()->id)->where("is_walikelas","Y")->get()->isNotEmpty() && DB::table("guru")->where("user_id",Auth::user()->id)->where("is_mapel","Y")->get()->isNotEmpty()){
      $kelas =  DB::table("kelas")->where("guru_id",DB::table("guru")->where("user_id",Auth::user()->id)->first()->id)->first();
      $mapel =  DB::table("mapel")->where("guru_id",DB::table("guru")->where("user_id",Auth::user()->id)->first()->id)->first();
      if($kelas || $mapel){
        $data = DB::table("nilai_pembelajaran")
        ->join("siswa", "siswa.id", '=', 'nilai_pembelajaran.siswa_id')
        ->join("mapel", "mapel.id", '=', 'nilai_pembelajaran.mapel_id')
        ->join("kelas", "kelas.id", '=', 'nilai_pembelajaran.kelas_id')
        ->select("nilai_pembelajaran.*", "siswa.nama_lengkap as nama_siswa","mapel.nama as nama_mapel","kelas.nama as nama_kelas")
        ->where("nilai_pembelajaran.kelas_id",$kelas->id)
        ->where("nilai_pembelajaran.mapel_id",$mapel->id)
        ->get();
      }else{
        $data = [];
      }
    }else if(DB::table("wali_murid")->where("user_id",Auth::user()->id)->get()->isNotEmpty()){
      $wali_id = DB::table("wali_murid")->where("user_id",Auth::user()->id)->first()->id;
      $students = DB::table("siswa")->where("wali_murid_id",$wali_id)->get();
      foreach($students as $student){
        $data = DB::table("nilai_pembelajaran")
        ->join("siswa", "siswa.id", '=', 'nilai_pembelajaran.siswa_id')
        ->join("mapel", "mapel.id", '=', 'nilai_pembelajaran.mapel_id')
        ->join("kelas", "kelas.id", '=', 'nilai_pembelajaran.kelas_id')
        ->select("nilai_pembelajaran.*", "siswa.nama_lengkap as nama_siswa","mapel.nama as nama_mapel","kelas.nama as nama_kelas")
        ->where("siswa.wali_murid_id",$student->wali_murid_id)
        ->get();
      }
    }else{
      $data = DB::table("nilai_pembelajaran")
      ->join("siswa", "siswa.id", '=', 'nilai_pembelajaran.siswa_id')
      ->join("mapel", "mapel.id", '=', 'nilai_pembelajaran.mapel_id')
      ->join("kelas", "kelas.id", '=', 'nilai_pembelajaran.kelas_id')
      ->select("nilai_pembelajaran.*", "siswa.nama_lengkap as nama_siswa","mapel.nama as nama_mapel","kelas.nama as nama_kelas")
      ->where("siswa.user_id",Auth::user()->id)
      ->get();
    }

    return Datatables::of($data)
      ->addColumn('aksi', function ($data) {
        return  '<div class="btn-group">' .
          '<a href="nilai-pembelajaran/edit/' . $data->id . '" class="btn btn-info btn-lg">'.
          '<label class="fa fa-pencil-alt"></label></a>' .
          '<a href="nilai-pembelajaran/hapus/'.$data->id.'" class="btn btn-danger btn-lg" title="hapus">' .
          '<label class="fa fa-trash"></label></a>';
      })->addColumn('is_show', function ($data) {
        if($data->is_show == "Y")
        return '<div class="btn-group">' .
        '<a href="nilai-pembelajaran/unacc?id='.$data->id.'&ulangan_harian='.$data->ulangan_harian.'&nilai_tugas='.$data->nilai_tugas.'&nilai_uts='.$data->nilai_uts.'&nilai_uas='.$data->nilai_uas.'" class="btn btn-warning btn-lg" title="hapus">' .
        'Batalkan</a></div>';
        else
        return '<div class="btn-group">' .
        '<a href="nilai-pembelajaran/acc?id='.$data->id.'&ulangan_harian='.$data->ulangan_harian.'&nilai_tugas='.$data->nilai_tugas.'&nilai_uts='.$data->nilai_uts.'&nilai_uas='.$data->nilai_uas.'"class="btn btn-success btn-lg" title="hapus">' .
        'ACC Sekarang</a></div>';
      })
      ->rawColumns(['aksi',"is_show"])
      ->addIndexColumn()
      ->make(true);
  }
  public function simpan(Request $req)
  {
      try {
        if(DB::table("guru")->where("user_id",Auth::user()->id)->where("is_mapel","Y")->get()->isNotEmpty()){
          $req->mapel_id = DB::table("mapel")->where("guru_id",DB::table("guru")->where("user_id",Auth::user()->id)->first()->id)->first()->id;
        }
        DB::table("nilai_pembelajaran")
        ->insert([
          "kelas_id" => $req->kelas_id,
          "mapel_id" => $req->mapel_id,
          "siswa_id" => $req->siswa_id,
          "ulangan_harian" => $req->ulangan_harian,
          "semester" => $req->semester,
          "nilai_tugas" => $req->nilai_tugas,
          "nilai_uts" => $req->nilai_uts,
          "nilai_uas" => $req->nilai_uas,
          "nilai_rata" => 0,
          "created_at" => Carbon::now('Asia/Jakarta'),
        ]);

          DB::commit();

        return response()->json(["status" => 1]);
      } catch (\Exception $e) {
        DB::rollback();
        return response()->json(["status" => 2, "message" => $e->getMessage()]);
      }
  }

  public function APIaccNilai(Request $req){
    try{
      DB::table("nilai_pembelajaran")
      ->where("kelas_id",$req->kelas_id)
      ->where("semester",$req->semester)
      ->update(["is_show"=>"Y"]);

      return response()->json(['status' => 1, "message"=> "berhasil di acc"]);
    } catch (\Exception $e) {
        DB::rollback();
        return response()->json(["status" => 2, "message" => $e->getMessage()]);
    }
  }

  public function accNilai(Request $req){
    try{
      $guru = DB::table("guru")->where("user_id",Auth::user()->id)->first();
      $req->kelas_id = DB::table("kelas")->where("guru_id",$guru->id)->first()->id;
      DB::table("nilai_pembelajaran")
      ->where("kelas_id",$req->kelas_id)
      ->where("semester",$req->semester)
      ->update(["is_show"=>"Y"]);

      return back()->with(['success' => 'nilai berhasil ditampilkan']);
    } catch (\Exception $e) {
        DB::rollback();
        return response()->json(["status" => 2, "message" => $e->getMessage()]);
    }
  }

  public function accOrUnacc(Request $req,$tipe){
    try{
      if($tipe == "acc"){
        $nilai_rata = ($req->ulangan_harian+$req->nilai_tugas+$req->nilai_uts+$req->nilai_uas)/4;
      DB::table("nilai_pembelajaran")
      ->where("id",$req->id)
      ->update(["is_show"=>"Y","nilai_rata"=>$nilai_rata]);

      return back()->with(['success' => 'berhasil di acc']);
      }else{
        DB::table("nilai_pembelajaran")
        ->where("id",$req->id)
        ->update(["is_show"=>"N","nilai_rata"=>0]);
  
        return back()->with(['success' => 'berhasil diubah']);
      }
    } catch (\Exception $e) {
        DB::rollback();
        return response()->json(["status" => 2, "message" => $e->getMessage()]);
    }
  }

  public function setRata(Request $req){
    try{
      $nilai_pembelajaran = DB::table("nilai_pembelajaran")
      ->where("id",$req->id)->first();
      $nilai_rata = ($nilai_pembelajaran->ulangan_harian+$nilai_pembelajaran->nilai_tugas+$nilai_pembelajaran->nilai_uts+$nilai_pembelajaran->nilai_uas)/4;
      DB::table("nilai_pembelajaran")
      ->where("id",$req->id)->update(["is_show"=>"Y","nilai_rata"=>$nilai_rata]);;
      return response()->json(["status" => 1, "message" => "berasil diubah"]);
    } catch (\Exception $e) {
        DB::rollback();
        return response()->json(["status" => 2, "message" => $e->getMessage()]);
    }
  }

  public function hapus($id)
  {
    DB::table("nilai_pembelajaran")
        ->where('id',$id)
        ->delete();

      return back()->with(['success' => 'Data berhasil dihapus']);
  }

  public function edit($id)
  {
    $data = DB::table("nilai_pembelajaran")->where("id",$id)->first();
    $students = DB::table('siswa')->get();
    $lessons = DB::table('mapel')->get();
    $classes = DB::table('kelas')->get();
    return view("nilai_pembelajaran.edit", compact('data','students','lessons','classes'));
  }

  public function update(Request $req)
  {
    try {
    $this->validate($req,[
      'kelas_id' => 'required|max:11',
      'mapel_id' => 'required|max:11',
      'siswa_id' => 'required|max:11',
      'ulangan_harian' => 'required|max:255',
      'semester' => 'required|max:255',
      'nilai_tugas' => 'required|max:255',
      'nilai_uts' => 'required|max:255',
      'nilai_uas' => 'required|max:255',
    ]);

    $data = DB::table("nilai_pembelajaran")->where('id',$req->id);
    $data->update([
    "kelas_id" => $req->kelas_id,
    "mapel_id" => $req->mapel_id,
    "siswa_id" => $req->siswa_id,
    "ulangan_harian" => $req->ulangan_harian,
    "semester" => $req->semester,
    "nilai_tugas" => $req->nilai_tugas,
    "nilai_uts" => $req->nilai_uts,
    "nilai_uas" => $req->nilai_uas,
    ]);
    return back()->with(['success' => 'Data berhasil diupdate']);
  } catch (\Exception $e) {
    DB::rollback();
    return response()->json(["status" => 2, "message" => $e->getMessage()]);
  }
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

  
  public function getData(Request $req){
    try{
      if($req->id){
        $data = DB::table('nilai_pembelajaran')
        ->join("siswa", "siswa.id", '=', 'nilai_pembelajaran.siswa_id')
        ->join("mapel", "mapel.id", '=', 'nilai_pembelajaran.mapel_id')
        ->join("kelas", "kelas.id", '=', 'nilai_pembelajaran.kelas_id')
        ->select("nilai_pembelajaran.*", "siswa.nama_lengkap as nama_siswa","mapel.nama as nama_mapel","kelas.nama as nama_kelas")
        ->where("nilai_pembelajaran.id",$req->id)->first();
      }else{
        $data = DB::table('nilai_pembelajaran')
          ->join("siswa", "siswa.id", '=', 'nilai_pembelajaran.siswa_id')
          ->join("mapel", "mapel.id", '=', 'nilai_pembelajaran.mapel_id')
          ->join("kelas", "kelas.id", '=', 'nilai_pembelajaran.kelas_id')
          ->select("nilai_pembelajaran.*", "siswa.nama_lengkap as nama_siswa","mapel.nama as nama_mapel","kelas.nama as nama_kelas")
          ->when($req->kelas_id, function($q, $kelas_id) {
              return $q->where('nilai_pembelajaran.kelas_id',$kelas_id);
          })
          ->when($req->mapel_id, function($q, $mapel_id) {
            return $q->where('nilai_pembelajaran.mapel_id',$mapel_id);
          })
          ->when($req->siswa_id, function($q, $siswa_id) {
          return $q->where('nilai_pembelajaran.siswa_id',$siswa_id);
          })
          ->when($req->semester, function($q, $semester) {
            return $q->where('nilai_pembelajaran.semester',$semester);
          })
          ->when($req->is_show, function($q, $is_show) {
            return $q->where('nilai_pembelajaran.is_show',$is_show);
          })
          ->get();
      }
      return response()->json(["status" => 1, "data" => $data]);
    }catch(\Exception $e){
      return response()->json(["status" => 2, "message" => $e->getMessage()]);
    }
  }

  public function insertOrUpdate(Request $req){
    if($req->id){
      $this->update($req);
      return response()->json(["status" => 1]);
    }else{
      $this->simpan($req);
      return response()->json(["status" => 1]);
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
