<?php

namespace App\Http\Controllers\Perpustakaan;
use App\Http\Controllers\Controller;
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

class KategoriBukuController extends Controller
{
  public function index()
  {
    $employees = DB::table("pegawai")->where('is_perpus','Y')->get();  
    return view('kategori_buku.index',compact('employees'));
  }

  public function datatable()
  {
    $data = DB::table('perpus_kategori')
      ->get();

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
          '<a href="kategori-buku/edit/' . $data->id . '" class="btn btn-info btn-lg">'.
          '<label class="fa fa-pencil-alt"></label></a>' .
          '<a href="kategori-buku/hapus/'.$data->id.'" class="btn btn-danger btn-lg" title="hapus">' .
          '<label class="fa fa-trash"></label></a>' .
          '</div>';
      })->addColumn('jumlah_buku', function ($data) {
        $jumlahBuku = DB::table("perpus_katalog")->where("perpus_kategori_id", $data->id)->count();
        return $jumlahBuku;
      })
      ->addColumn('created_by', function ($data) {
        $employee = DB::table("pegawai")->where("id",$data->pegawai_id)->first();
        return $employee->nama_lengkap;
      })
      ->rawColumns(['aksi', 'jumlah_buku','created_by'])
      ->addIndexColumn()
      ->make(true);
  }

  public function simpan(Request $req)
  {
      try {
        DB::table("perpus_kategori")
          ->insert([
            "pegawai_id" => $req->pegawai_id,
            "nama" => $req->nama,
          ]);
          DB::commit();

        return response()->json(["status" => 1]);
      } catch (\Exception $e) {
        DB::rollback();
        return response()->json(["status" => 7, "message" => "error"+$e]);
      }
  }

  public function getData(Request $req){
    try{
      if($req->id){
        $data = DB::table('perpus_kategori')->where("id",$req->id)->first();
      }else{
        $data = DB::table('perpus_kategori')
          ->when($req->nama, function($q, $nama) {
              return $q->where('nama', 'like','%'.$nama.'%');
          })->get();
      }

      return response()->json(["status" => 1, "data" => $data]);
    }catch(\Exception $e){
      return response()->json(["status" => 2, "message" => $e->getMessage()]);
    }
  }

  public function hapus($id)
  {
    DB::table("perpus_kategori")
        ->where('id',$id)
        ->delete();

      return back()->with(['success' => 'Data berhasil dihapus']);
  }

  public function edit($id)
  {
    $data = DB::table("perpus_kategori")->where("id", $id)->first();
    // dd($data);
    return view("kategori_buku.edit", compact('data'));
    
  }

  public function update(Request $req)
  {
    $this->validate($req,[
      'nama' => 'required|max:255',
    ]);

    $imgPath = null;
    $tgl = Carbon::now('Asia/Jakarta');
    $folder = $tgl->year . $tgl->month . $tgl->timestamp;
    $childPath ='image/uploads/buku/';
    $path = $childPath;

    $file = $req->file('foto');
    $name = null;
    $data = DB::table("perpus_kategori")->where('id',$req->id);
    if ($file != null) {
      $name = $folder . '.' . $file->getClientOriginalExtension();
      $file->move($path, $name);
      $imgPath = $childPath . $name;
      $data->update(['nama'=>$req->nama]);
    } else {
      $data->update(['nama'=>$req->nama]);
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
