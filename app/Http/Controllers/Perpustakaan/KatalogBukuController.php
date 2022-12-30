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
use Exception;
use File;
use PhpParser\Node\Stmt\TryCatch;
use Yajra\Datatables\Datatables;

use Response;

class KatalogBukuController extends Controller
{
  public function index()
  {
    $categories = DB::table("perpus_kategori")->get();
    $employees = DB::table("pegawai")->where('is_perpus','Y')->get();
    return view('katalog_buku.index',compact('categories','employees'));
  }

  public function datatable()
  {
    $byId = DB::table('perpus_katalog')->where("stok_buku",">","0")
      ->get();

    $full = DB::table('perpus_katalog')->get();

    $data = Auth::user()->role_id == 1 || DB::table("pegawai")->where("user_id",Auth::user()->id)->where("is_perpus","Y" )->get()->isNotEmpty() ? $full : $byId;

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
          '<a href="katalog-buku/edit/' . $data->id . '" class="btn btn-info btn-lg">'.
          '<label class="fa fa-pencil-alt"></label></a>' .
          '<a href="katalog-buku/hapus/'.$data->id.'" class="btn btn-danger btn-lg" title="hapus">' .
          '<label class="fa fa-trash"></label></a>' .
          '</div>';
      })->addColumn('foto', function ($data) {
        $url= asset($data->foto);
        return '<img src="' . $url . '" style="height: 80px; width:80px; border-radius: 0px;" class="img-responsive"> </img>';
      })
      ->addColumn('perpus_kategori_id', function ($data) {
        $category= DB::table("perpus_kategori")->where("id",$data->perpus_kategori_id)->first();
        return $category->nama;
      })
      ->addColumn('created_by', function ($data) {
        $employee = DB::table("pegawai")->where("id",$data->pegawai_id)->first();
        return $employee->nama_lengkap;
      })
      ->rawColumns(['aksi', 'foto','perpus_kategori_id','created_by'])
      ->addIndexColumn()
      ->make(true);
  }

  public function getData(Request $req){
    try{
      if($req->id){
        $data = DB::table('perpus_katalog')
        ->join("perpus_kategori", "perpus_kategori.id", '=', 'perpus_katalog.perpus_kategori_id')
        ->where("id",$req->id)->first();
      }else{
        $data = DB::table('perpus_katalog')
          ->join("perpus_kategori", "perpus_kategori.id", '=', 'perpus_katalog.perpus_kategori_id')
          ->where("stok_buku",">","0")
          ->when($req->judul, function($q, $judul) {
            return $q->where('judul', 'like','%'.$judul.'%');
          })
          ->when($req->perpus_kategori_id, function($q, $perpus_kategori_id) {
            return $q->where('perpus_kategori_id',$perpus_kategori_id);
          })
          ->when($req->author, function($q, $author) {
              return $q->where('author', 'like','%'.$author.'%');
          })
          ->select("perpus_katalog.*","perpus_kategori.nama as kategori_nama","perpus_kategori.id as kategori_id")
          ->get();
      }

      return response()->json(["status" => 1, "data" => $data]);
    }catch(\Exception $e){
      return response()->json(["status" => 2, "message" => $e->getMessage()]);
    }
  }

  public function simpan(Request $req)
  {
      try {
        $imgPath = null;
        $tgl = Carbon::now('Asia/Jakarta');
        $folder = $tgl->year . $tgl->month . $tgl->timestamp;
        $childPath ='image/uploads/buku/';
        $path = $childPath;

        $file = $req->file('image');
        $name = null;
        if ($file != null) {
          $name = $folder . '.' . $file->getClientOriginalExtension();
          $file->move($path, $name);
          $imgPath = $childPath . $name;
        } else {
            return 'already exist';
        }
        
        DB::table("perpus_katalog")
          ->insert([
            "pegawai_id" => $req->pegawai_id,
            "foto" => $imgPath,
            "judul" => $req->judul,
            "author" => $req->author,
            "perpus_kategori_id" => $req->perpus_kategori_id,
            "bahasa" => $req->bahasa,
            "total_halaman" => $req->total_halaman,
          ]);
          DB::commit();

        return response()->json(["status" => 1]);
      } catch (\Exception $e) {
        DB::rollback();
        return response()->json(["status" => 7, "message" => "aman"]);
      }
  }

  public function hapus($id)
  {
    DB::table("perpus_peminjaman_katalog")
    ->where('perpus_katalog_id',$id)
    ->delete();

    DB::table("perpus_katalog")
        ->where('id',$id)
        ->delete();

      return back()->with(['success' => 'Data berhasil dihapus']);
  }

  public function edit($id)
  {
    $data = DB::table("perpus_katalog")->where("id", $id)->first();
    $categories = DB::table("perpus_kategori")->get();
    $category_id = DB::table("perpus_kategori")->where('id',$data->perpus_kategori_id)->first()->id;
    // dd($data);
    return view("katalog_buku.edit", compact('data','category_id','categories'));
    
  }

  public function update(Request $req)
  {
    try{
    $this->validate($req,[
      'judul' => 'required|max:255',
      'author' => 'required|max:255',
      'perpus_kategori_id' => 'required|max:255',
      'bahasa' => 'required|max:255',
      'total_halaman' => 'required|max:255',
      'stok_buku' => 'required|max:100',
    ]);

    $imgPath = null;
    $tgl = Carbon::now('Asia/Jakarta');
    $folder = $tgl->year . $tgl->month . $tgl->timestamp;
    $childPath ='image/uploads/perpus/';
    $path = $childPath;

    $file = $req->file('image');
    $name = null;
    $data = DB::table("perpus_katalog")->where('id',$req->id);
    if ($file != null) {
      $name = $folder . '.' . $file->getClientOriginalExtension();
      $file->move($path, $name);
      $imgPath = $childPath . $name;
      $data->update(['stok_buku'=>$req->stok_buku,'judul'=>$req->judul,'author'=>$req->author,'bahasa'=>$req->bahasa,'total_halaman'=>$req->total_halaman,'perpus_kategori_id'=>$req->perpus_kategori_id,'foto'=>$imgPath]);
    } else {
      $data->update(['stok_buku'=>$req->stok_buku,'judul'=>$req->judul,'author'=>$req->author,'bahasa'=>$req->bahasa,'total_halaman'=>$req->total_halaman,'perpus_kategori_id'=>$req->perpus_kategori_id]);
    }

    // dd($data);
    return back()->with(['success' => 'Data berhasil diupdate']);
  } catch (\Exception $e) {
    DB::rollback();
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

  public function delete($id){
    if($id){
      DB::table("perpus_peminjaman_katalog")
      ->where('perpus_katalog_id',$id)
      ->delete();
  
      $data = DB::table("perpus_katalog")
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
