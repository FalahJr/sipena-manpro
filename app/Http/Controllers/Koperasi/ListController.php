<?php

namespace App\Http\Controllers\Koperasi;
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

class ListController extends Controller
{
  public function index()
  {
    $employees = DB::table("pegawai")->where("is_koperasi","Y")->get();
    return view('list_koperasi.index',compact('employees'));
  }

  public function datatable()
  {
    $data = DB::table('koperasi_list')->get();


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
          '<a href="list-koperasi/edit/' . $data->id . '" class="btn btn-info btn-lg">'.
          '<label class="fa fa-pencil-alt"></label></a>' .
          '<a href="list-koperasi/hapus/'.$data->id.'" class="btn btn-danger btn-lg" title="hapus">' .
          '<label class="fa fa-trash"></label></a>';
      })
      ->addColumn('pegawai_id', function ($data) {
        $pegawai = DB::table('pegawai')->where('id',$data->pegawai_id)->first();
        return $pegawai->nama_lengkap;
      })
      ->rawColumns(['aksi','pegawai_id'])
      ->addIndexColumn()
      ->make(true);
  }

  public function toBayar($id){
    $data = DB::table('kantin')->where("id",$id)->first();
    return view('kantin.pembayaran',compact("data"));
  }

  public function bayar(Request $req)
  {
        $this->validate($req,[
          'nama_pembeli' => 'required|max:255',
          'keterangan' => 'required|max:255',
          'total_harga' => 'required|max:255',
        ]);
        $tgl = Carbon::now('Asia/Jakarta');
        DB::table("kantin_penjualan")
          ->insert([
            "kantin_id" => $req->kantin_id,
            "user_id" => $req->user_id,
            "nama_pembeli" => $req->nama_pembeli,
            "keterangan" => $req->keterangan,
            "harga_total" => $req->total_harga,
            "created_at" => $tgl,
          ]);
        
          return back()->with(['success' => 'Data berhasil diupdate']);
  }

  public function simpan(Request $req)
  {
      try {
      if(DB::table("pegawai")->where("is_koperasi","Y")->where("user_id",Auth::user()->id)->get()->isNotEmpty()){
        $req->pegawai_id =  DB::table("pegawai")->where("user_id",Auth::user()->id)->first()->id;
      }
        DB::table("koperasi_list")
        ->insert([
          "nama" => $req->nama,
          "harga" => $req->harga,
          "pegawai_id" => $req->pegawai_id,
        ]);

          DB::commit();

        return response()->json(["status" => 1]);
      } catch (\Exception $e) {
        DB::rollback();
        return response()->json(["status" => 7, "message" => $e]);
      }
  }
  public function getData(){
    $data = DB::table('koperasi_list')->get();
    return response()->json(["status" => 1,"data"=>$data]);
  }
  public function insertOrUpdate(Request $req){
    try{
      if($req->id){
        $this->update($req);
        return response()->json(["status" => 1,"message"=>"berhasil diubah"]);
      }else{
        $this->simpan($req);
        return response()->json(["status" => 1,"message"=>"berhasil dibuat"]);
      }

    }catch(\Exception $e){
      return response()->json(["status" => 2, "message" => $e->getMessage()]);
    }
  }
  
  public function countSaldo(){
    $data = DB::table("koperasi")->first();
    if($data){
      return response()->json(["status" => 1, "data" => $data->saldo]);
    }else{
      return response()->json(["status" => 2, "message" => "koperasi tidak ditemukan"]);
    }
  }

  public function delete($id){
    try{
      $data = DB::table("koperasi_list")
      ->where('id',$id);
      if(!$data->first()){
        return response()->json(["status" => 2, "message" => "data tidak ditemukan"]);
      }
    $data->delete();
    return response()->json(["status" => 1, "message" => "data berhasil dihapus"]);
  }catch(\Exception $e){
    return response()->json(["status" => 2, "message" => $e->getMessage()]);
  }
  }
  public function hapus($id)
  {
    DB::table("koperasi_list")
        ->where('id',$id)
        ->delete();

      return back()->with(['success' => 'Data berhasil dihapus']);
  }

  public function edit($id)
  {
    $data = DB::table("koperasi_list")->where("id", $id)->first();
    $employees = DB::table('pegawai')->where("is_koperasi","Y")->get();
    $employee_id = DB::table('pegawai')->where("id",$data->pegawai_id)->first()->id;
    return view("list_koperasi.edit", compact('data','employees','employee_id'));
  }

  public function update(Request $req)
  {
    $this->validate($req,[
      'nama' => 'required|max:255',
      'harga' => 'required|max:255',
      'pegawai_id' => 'required|max:255',
    ]);

    $data = DB::table("koperasi_list")->where('id',$req->id);
    $data->update(['nama'=>$req->nama,'pegawai_id'=>$req->pegawai_id,'harga'=>$req->harga]);
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
