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

class PinjamBukuController extends Controller
{
  public function index()
  {
    return view('pinjam_buku.index');
  }

  public function datatable()
  {
    $data = DB::table('perpus_peminjaman')->where("is_kembali","N")
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
          '<a href="pinjam-buku/edit/' . $data->id . '" class="btn btn-info btn-lg">'.
          '<label class="fa fa-pencil-alt"></label></a>' .
          '<a href="/admin/pinjam-buku/hapus/'.$data->id.'" class="btn btn-danger btn-lg" title="hapus">' .
          '<label class="fa fa-trash"></label></a>' .
          '</div>';
      })->addColumn('buku', function ($data) {
        $items = DB::table("perpus_peminjaman_katalog")->where("perpus_peminjaman_id", $data->id)->get();
        $urlBook = null;
        foreach($items as $item){
          $katalog = DB::table("perpus_katalog")->where("id", $item->perpus_katalog_id)->first();
          $urlBook .= '<a href="javascript:void(0)" data-id="'.$katalog->id.'" class="showBook" title="show">' . $katalog->judul .'</a><br>';
        }

        return $urlBook;

      })->addColumn('pegawai_id', function ($data) {
        $pegawai = DB::table("pegawai")->where("id", $data->pegawai_id)->first();
        return $pegawai->nama_lengkap;
      })
      ->rawColumns(['aksi', 'buku','pegawai_id'])
      ->addIndexColumn()
      ->make(true);
  }

  public function show($id){
    $data = DB::table("perpus_katalog")->where("id",$id)->first();
    return response()->json(['data'=>$data]);
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

        $pegawai = DB::table('pegawai')->where('user_id',$req->user_id)->get();
        
        if(!empty($pegawai->id)){
            $created_by = $pegawai->nama_lengkap;
            $pegawai = $pegawai->id;
        }else{
            $created_by = "admin";
            $pegawai = null;
        }
        
        DB::table("perpus_peminjaman")
          ->insert([
            "pegawai_id" => $pegawai,
            "foto" => $imgPath,
            "judul" => $req->judul,
            "author" => $req->author,
            "bahasa" => $req->bahasa,
            "total_halaman" => $req->total_halaman,
            "created_by" => $created_by,
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
    ->where('perpus_peminjaman_id',$id)
    ->delete();

    DB::table("perpus_peminjaman")
        ->where('id',$id)
        ->delete();

      return back()->with(['success' => 'Data berhasil dihapus']);
  }

  public function edit($id)
  {
    $books = array(0=>null,1=>null,2=>null);
    $data = DB::table("perpus_peminjaman")->where("id", $id)->first();
    $pinjamKatalogs = DB::table("perpus_peminjaman_katalog")->where("perpus_peminjaman_id", $id)->get();
    $i=0;
    foreach($pinjamKatalogs as $katalogs){
      $books[$i] = DB::table("perpus_katalog")->where("id", $katalogs->perpus_katalog_id)->first()->id;
      $i++;
    }
    
    $items = DB::table("perpus_katalog")->get();
    // dd($data);
    return view("pinjam_buku.edit", compact('data','books','items'));
    
  }

  public function update(Request $req)
  {
    $this->validate($req,[
      'perpus_katalog_id' => 'required|max:3',
      'tanggal_peminjaman' => 'required|max:255',
      'tanggal_pengembalian' => 'required|max:255',
    ]);
    DB::table("perpus_peminjaman_katalog")->where('perpus_peminjaman_id',$req->id)->delete();
    foreach($req->perpus_katalog_id as $idKatalog){
      DB::table("perpus_peminjaman_katalog")->insert([
        "perpus_peminjaman_id" => $req->id,
        "perpus_katalog_id" => $idKatalog,
      ]);
    }

    DB::table("perpus_peminjaman")->where("id",$req->id)->update(['tanggal_peminjaman'=>$req->tanggal_peminjaman,'tanggal_pengembalian'=>$req->tanggal_pengembalian]);
    
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
