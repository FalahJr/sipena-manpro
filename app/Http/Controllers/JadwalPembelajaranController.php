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

  public function datatable(Request $req)
  { 
      // if(DB::table("siswa")->where("user_id",Auth::user()->id)->get()->isNotEmpty()){
        if(Auth::user()->role_id == 2) {

        $kelas_id = DB::table("siswa")->where("user_id",Auth::user()->id)->first()->kelas_id;
        $data = DB::table('jadwal_pembelajaran')
        ->where("kelas_id",$kelas_id)
        ->get();
        }else if(Auth::user()->role_id == 4){

            $guru = DB::table("guru")->where("user_id",Auth::user()->id)->first();
            $mapel = DB::table("mapel")->where("guru_id",$guru->id)->first();
            $data = DB::table('jadwal_pembelajaran')
            ->where("mapel_id",$mapel->id)
            ->get();
        // }
      }else{
        $data = DB::table('jadwal_pembelajaran')
        ->get();
      }


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
  public function getData(Request $req){
    try{
        $datas = DB::table('jadwal_pembelajaran')
        ->join("kelas","kelas.id","=","jadwal_pembelajaran.kelas_id")
        ->join("mapel","mapel.id","=","jadwal_pembelajaran.mapel_id")
        ->select("jadwal_pembelajaran.*","kelas.nama as kelas_nama","kelas.id as kelas_id","mapel.nama as mapel_nama","mapel.id as mapel_id")
        ->when($req->mapel_id,function($q,$idMapel){
          return $q->where("jadwal_pembelajaran.mapel_id",$idMapel);
        })
        ->when($req->kelas_id,function($q,$idMapel){
          return $q->where("jadwal_pembelajaran.kelas_id",$idMapel);
        })
        ->get();

        $senin = [];
        $selasa = [];
        $rabu = [];
        $kamis = [];
        $jumat = [];
        $sabtu = [];
        
        foreach($datas as $data){
            if($data->jadwal_hari == "Senin"){
              array_push($senin, $data);
            }
            if($data->jadwal_hari == "Selasa"){
              array_push($selasa, $data);
            }
            if($data->jadwal_hari == "Rabu"){
              array_push($rabu, $data);
            }
            if($data->jadwal_hari == "Kamis"){
              array_push($kamis, $data);
            }
            if($data->jadwal_hari == "Jumat"){
              array_push($jumat, $data);
            }
            if($data->jadwal_hari == "Sabtu"){
              array_push($sabtu, $data);
            }
        }   
        $datas = (object) array('Senin' => $senin,'Selasa' => $selasa,'Rabu' => $rabu,'Kamis' => $kamis,'Jumat' => $jumat,'Sabtu' => $sabtu);

        if($datas){
          return response()->json(["status" => 1, "data"=>$datas]);
        }else{
          return response()->json(["status" => 2, "message"=>"data tidak ditemukan"]);
        }
    } catch (\Exception $e) {
      DB::rollback();
      return response()->json(["status" => 2, "message" => $e->getMessage()]);
    }
  }

  public function hari_ini(){
    $hari = date ("D");
   
    switch($hari){
      case 'Sun':
        $hari_ini = "Minggu";
      break;
   
      case 'Mon':			
        $hari_ini = "Senin";
      break;
   
      case 'Tue':
        $hari_ini = "Selasa";
      break;
   
      case 'Wed':
        $hari_ini = "Rabu";
      break;
   
      case 'Thu':
        $hari_ini = "Kamis";
      break;
   
      case 'Fri':
        $hari_ini = "Jumat";
      break;
   
      case 'Sat':
        $hari_ini = "Sabtu";
      break;
      
      default:
        $hari_ini = "Tidak di ketahui";		
      break;
    }
   
    return $hari_ini;
   
  }

  public function getJadwalSekarang(Request $req){
    try{
        $datas = DB::table('jadwal_pembelajaran')
        ->join("kelas","kelas.id","=","jadwal_pembelajaran.kelas_id")
        ->join("mapel","mapel.id","=","jadwal_pembelajaran.mapel_id")
        ->select("jadwal_pembelajaran.*","kelas.nama as kelas_nama","kelas.id as kelas_id","mapel.nama as mapel_nama","mapel.id as mapel_id")
        ->when($req->mapel_id,function($q,$idMapel){
          return $q->where("jadwal_pembelajaran.mapel_id",$idMapel);
        })
        ->when($req->kelas_id,function($q,$idMapel){
          return $q->where("jadwal_pembelajaran.kelas_id",$idMapel);
        })
        ->get();

        $jadwalHari = [];
        
        foreach($datas as $data){
          if($data->jadwal_hari == $this->hari_ini()){
            array_push($jadwalHari, $data);
          }
        }   

        if($datas){
          return response()->json(["status" => 1, "data"=>$jadwalHari]);
        }else{
          return response()->json(["status" => 2, "message"=>"data tidak ditemukan"]);
        }
    } catch (\Exception $e) {
      DB::rollback();
      return response()->json(["status" => 2, "message" => $e->getMessage()]);
    }
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
      $req->jadwal_hari = ucfirst($req->jadwal_hari);

        $tes = DB::table("jadwal_pembelajaran")
          ->insert([
            "id" => $max,
            "mapel_id" => $req->mapel_id,
            "kelas_id" => $req->kelas_id,
            "jadwal_hari" => $req->jadwal_hari,
            "jadwal_waktu_mulai" => $req->jadwal_waktu_mulai,
            "jadwal_waktu_akhir" => $req->jadwal_waktu_akhir,
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
      'jadwal_waktu_mulai' => 'required',
      'jadwal_waktu_akhir' => 'required',
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