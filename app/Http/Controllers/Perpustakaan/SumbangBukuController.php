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

class SumbangBukuController extends Controller
{
  public function index()
  {
    return view('sumbang_buku.index');
  }

  public function datatable()
  {
    $data = DB::table('perpus_sumbang')
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
          '<a href="sumbang-buku/edit/' . $data->id . '" class="btn btn-info btn-lg">'.
          '<label class="fa fa-pencil-alt"></label></a>' .
          '<a href="/admin/sumbang-buku/hapus/'.$data->id.'" class="btn btn-danger btn-lg" title="hapus">' .
          '<label class="fa fa-trash"></label></a>' .
          '</div>';
      })->addColumn('foto', function ($data) {
        $url= asset($data->foto);
        return '<img src="' . $url . '" style="height: 80px; width:80px; border-radius: 0px;" class="img-responsive"> </img>';
      })->addColumn('user', function ($data) {
        $user = DB::table('user')->where('id',$data->user_id)->first();
        return $user->username;
      })
      ->rawColumns(['aksi', 'foto','user'])
      ->addIndexColumn()
      ->make(true);
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
        
        DB::table("perpus_sumbang")
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
    DB::table("perpus_sumbang")
        ->where('id',$id)
        ->delete();

      return back()->with(['success' => 'Data berhasil dihapus']);
  }

  public function edit($id)
  {
    $data = DB::table("perpus_sumbang")->where("id", $id)->first();
    // dd($data);
    return view("sumbang_buku.edit", compact('data'));
    
  }

  public function update(Request $req)
  {
    $this->validate($req,[
      'judul' => 'required|max:255',
      'author' => 'required|max:255',
      'bahasa' => 'required|max:255',
      'total_halaman' => 'required|max:255',
    ]);

    $imgPath = null;
    $tgl = Carbon::now('Asia/Jakarta');
    $folder = $tgl->year . $tgl->month . $tgl->timestamp;
    $childPath ='image/uploads/buku/';
    $path = $childPath;

    $file = $req->file('foto');
    $name = null;
    $data = DB::table("perpus_sumbang")->where('id',$req->id);
    if ($file != null) {
      $name = $folder . '.' . $file->getClientOriginalExtension();
      $file->move($path, $name);
      $imgPath = $childPath . $name;
      $data->update(['judul'=>$req->judul,'author'=>$req->author,'bahasa'=>$req->bahasa,'total_halaman'=>$req->total_halaman,'foto'=>$imgPath]);
    } else {
      $data->update(['judul'=>$req->judul,'author'=>$req->author,'bahasa'=>$req->bahasa,'total_halaman'=>$req->total_halaman]);
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
