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

use App\Http\Controllers\NotifikasiController as Notifikasi;


use Response;

class SumbangBukuController extends Controller
{
  public function index()
  {
    $employees = DB::table("pegawai")->where("is_perpus","Y")->get();
    $users = DB::table("user")->get();
    $categories = DB::table("perpus_kategori")->get();
    return view('sumbang_buku.index',compact('employees','users','categories'));
  }

  public function datatable()
  {

    $full = DB::table('perpus_sumbang')
      ->get();

    $byId = DB::table('perpus_sumbang')
      ->where("user_id",Auth::user()->id)
      ->get();

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
          '<a href="sumbang-buku/edit/' . $data->id . '" class="btn btn-info btn-lg">'.
          '<label class="fa fa-pencil-alt"></label></a>' .
          '<a href="sumbang-buku/hapus/'.$data->id.'" class="btn btn-danger btn-lg" title="hapus">' .
          '<label class="fa fa-trash"></label></a>' .
          '</div>';
      })->addColumn('foto', function ($data) {
        $url= asset($data->foto);
        return '<img src="' . $url . '" style="height: 80px; width:80px; border-radius: 0px;" class="img-responsive"> </img>';
      })->addColumn('user', function ($data) {
        $user = DB::table('user')->where('id',$data->user_id)->first();
        return $user->username;
      })->addColumn('kategori', function ($data) {
        $kategori = DB::table('perpus_kategori')->where('id',$data->perpus_kategori_id)->first();
        return $kategori->nama;
      })
      ->addColumn('pegawai', function ($data) {
        if($data->pegawai_id){
        $employee = DB::table("pegawai")->where("id",$data->pegawai_id)->first()->nama_lengkap;
        return $employee;
      }else{
        $pegawai = DB::table("pegawai")->where("user_id",Auth::user()->id)->where("is_perpus","Y")->first();
      if($pegawai){
        return '<a href="' . url('admin/sumbang-buku/acc?pegawai_id='.$pegawai->id.'&id='.$data->id). '" class="badge badge-warning p-2 badge-lg">ACC Sekarang</a>';
      }else{
        return '<span class="badge badge-warning p-2 badge-lg">PROSES</span>';
      }
        }
      })
      ->rawColumns(['aksi', 'foto','user','kategori','pegawai'])
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
        if($req->user_id == null && $req->pegawai_id == null){
          $req->user_id = Auth::user()->id;
          $req->pegawai_id = null;
        }

        if($req->pegawai_id){
        DB::table("perpus_sumbang")
          ->insert([
            "user_id" => $req->user_id,
            "pegawai_id" => $req->pegawai_id,
            "perpus_kategori_id" => $req->perpus_kategori_id,
            "foto" => $imgPath,
            "judul" => $req->judul,
            "author" => $req->author,
            "bahasa" => $req->bahasa,
            "total_halaman" => $req->total_halaman,
          ]);
          DB::table("perpus_katalog")
          ->insert([
            "pegawai_id" => $req->pegawai_id,
            "perpus_kategori_id" => $req->perpus_kategori_id,
            "foto" => $imgPath,
            "judul" => $req->judul,
            "author" => $req->author,
            "bahasa" => $req->bahasa,
            "total_halaman" => $req->total_halaman,
          ]);
        }else{
          DB::table("perpus_sumbang")
          ->insert([
            "user_id" => $req->user_id,
            // "pegawai_id" => null,
            "perpus_kategori_id" => $req->perpus_kategori_id,
            "foto" => $imgPath,
            "judul" => $req->judul,
            "author" => $req->author,
            "bahasa" => $req->bahasa,
            "total_halaman" => $req->total_halaman,
          ]);
        }

          DB::commit();

        return response()->json(["status" => 1]);
      } catch (\Exception $e) {
        DB::rollback();
        return response()->json(["status" => 2, "message" =>$e]);
      }
  }

  public function hapus($id)
  {
    DB::table("perpus_sumbang")
        ->where('id',$id)
        ->delete();

      return back()->with(['success' => 'Data berhasil dihapus']);
  }

  public function delete($id){
    if($id){
      $data = DB::table("perpus_sumbang")
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

  public function edit($id)
  {
    $data = DB::table("perpus_sumbang")->where("id", $id)->first();
    $employees = DB::table("pegawai")->where("is_perpus","Y")->get();
    $users = DB::table("user")->get();
    $categories = DB::table("perpus_kategori")->get();
    // dd($data);
    return view("sumbang_buku.edit", compact('data','employees','users','categories'));
    
  }


  public function APIaccSumbang(Request $req){
    try{
      if($req->id == null && $req->pegawai_id == null){
        return response()->json(["status" => 2, "message" => "required peprus_sumbang.id and pegawai_id"]);
      }
        $data = DB::table('perpus_sumbang')
        ->whereNull("perpus_sumbang.pegawai_id")
        ->join("perpus_kategori", "perpus_kategori.id", '=', 'perpus_sumbang.perpus_kategori_id')
        ->select("perpus_sumbang.*")
        ->when($req->id, function($q, $id) {
          return $q->where('perpus_sumbang.id',$id);
        })
        ->first();
        if($data){
        DB::table('perpus_sumbang')->where("id",$req->id)->update(["pegawai_id"=>$req->pegawai_id]);
        DB::table("perpus_katalog")
          ->insert([
            "pegawai_id" => $req->pegawai_id,
            "perpus_kategori_id" => $data->perpus_kategori_id,
            "foto" => $data->foto,
            "judul" => $data->judul,
            "author" => $data->author,
            "bahasa" => $data->bahasa,
            "total_halaman" => $data->total_halaman,
          ]);
          Notifikasi::push_notifikasi($data->user_id,"Sumbang Buku","Sumbangan buku anda berhasil di konfirmasi pegawai, buku anda sekarang ada pada katalog perpus");
          return response()->json(["status" => 1, "message" => "berhasil di acc"]);
          }else{
            return response()->json(["status" => 2, "message" => "perpus_sumbang.id tidak ditemukan"]);
          }
        }catch(\Exception $e){
          return response()->json(["status" => 2, "message" => $e->getMessage()]);
        }
  }

  public function accSumbang(Request $req){
    try{
        $data = DB::table('perpus_sumbang')
        ->whereNull("perpus_sumbang.pegawai_id")
        ->join("perpus_kategori", "perpus_kategori.id", '=', 'perpus_sumbang.perpus_kategori_id')
        ->select("perpus_sumbang.*")
        ->when($req->id, function($q, $id) {
          return $q->where('perpus_sumbang.id',$id);
        })
        ->first();
        DB::table('perpus_sumbang')->where("id",$req->id)->update(["pegawai_id"=>$req->pegawai_id]);
        DB::table("perpus_katalog")
          ->insert([
            "pegawai_id" => $req->pegawai_id,
            "perpus_kategori_id" => $data->perpus_kategori_id,
            "foto" => $data->foto,
            "judul" => $data->judul,
            "author" => $data->author,
            "bahasa" => $data->bahasa,
            "total_halaman" => $data->total_halaman,
          ]);
          Notifikasi::push_notifikasi($data->user_id,"Sumbang Buku","Sumbangan buku anda berhasil di konfirmasi pegawai, buku anda sekarang ada pada katalog perpus");
          return back()->with(['success' => 'berhasil di acc']);
        }catch(\Exception $e){
          return response()->json(["status" => 2, "message" => $e->getMessage()]);
        }
  }

  public function getData(Request $req){
    try{
      if($req->id){
        $data = DB::table('perpus_sumbang')
        ->join("user", "user.id", '=', 'perpus_sumbang.user_id')
        ->join("role", "user.role_id", '=', 'role.id')
        ->join("perpus_kategori", "perpus_kategori.id", '=', 'perpus_sumbang.perpus_kategori_id')
        ->select("perpus_sumbang.*","perpus_kategori.nama as kategori_nama","perpus_kategori.id as kategori_id","user.role_id","role.nama as role_nama")
        ->when($req->id, function($q, $id) {
          return $q->where('perpus_sumbang.id',$id);
        })
        ->first();
        if($data){
          if($data->role_id == 1){
            $data->user_nama = "admin";
          } else if($data->role_id == 2) {
              $cekdata = DB::table("siswa")->where('user_id', $data->user_id)->first();
              if($cekdata == null) {
                $data->user_nama = "-";
              } else {
                $data->user_nama = $cekdata->nama_lengkap;
              }
          } else if($data->role_id == 3) {
              $cekdata = DB::table("wali_murid")->where('user_id', $data->user_id)->first();
    
                       if($cekdata == null) {
                $data->user_nama = "-";
              } else {
                $data->user_nama = $cekdata->nama_lengkap;
              }
          } else if($data->role_id == 4) {
              $cekdata = DB::table("guru")->where('user_id', $data->user_id)->first();
    
                       if($cekdata == null) {
                $data->user_nama = "-";
              } else {
                $data->user_nama = $cekdata->nama_lengkap;
              }
          } else if($data->role_id == 5) {
              $cekdata = DB::table("pegawai")->where('user_id', $data->user_id)->first();
    
                       if($cekdata == null) {
                $data->user_nama = "-";
              } else {
                $data->user_nama = $cekdata->nama_lengkap;
              }
          } else if($data->role_id == 6) {
              $cekdata = DB::table("kepala_sekolah")->where('user_id', $data->user_id)->first();
    
                       if($cekdata == null) {
                $data->user_nama = "-";
              } else {
                $data->user_nama = $cekdata->nama_lengkap;
              }
          } else if($data->role_id == 7) {
              $cekdata = DB::table("dinas_pendidikan")->where('user_id', $data->user_id)->first();
                       if($cekdata == null) {
                $data->user_nama = "-";
              } else {
                $data->user_nama = $cekdata->nama_lengkap;
              }
          }
        }
        return response()->json(["status" => 1, "data" => $data]);
      }else{
        $datas = DB::table('perpus_sumbang')
        ->whereNull("perpus_sumbang.pegawai_id")
        ->join("user", "user.id", '=', 'perpus_sumbang.user_id')
        ->join("role", "user.role_id", '=', 'role.id')
        ->join("perpus_kategori", "perpus_kategori.id", '=', 'perpus_sumbang.perpus_kategori_id')
        ->select("perpus_sumbang.*","perpus_kategori.nama as kategori_nama","perpus_kategori.id as kategori_id","user.role_id","role.nama as role_nama")
        ->get();
      foreach($datas as $data){
        if($data->role_id == 1) {
          $data->user_nama = "admin";
      } else if($data->role_id == 2) {
          $cekdata = DB::table("siswa")->where('user_id', $data->user_id)->first();

                   if($cekdata == null) {
                $data->user_nama = "-";
              } else {
                $data->user_nama = $cekdata->nama_lengkap;
              }
      } else if($data->role_id == 3) {
          $cekdata = DB::table("wali_murid")->where('user_id', $data->user_id)->first();

                   if($cekdata == null) {
                $data->user_nama = "-";
              } else {
                $data->user_nama = $cekdata->nama_lengkap;
              }
      } else if($data->role_id == 4) {
          $cekdata = DB::table("guru")->where('user_id', $data->user_id)->first();

                   if($cekdata == null) {
                $data->user_nama = "-";
              } else {
                $data->user_nama = $cekdata->nama_lengkap;
              }
      } else if($data->role_id == 5) {
          $cekdata = DB::table("pegawai")->where('user_id', $data->user_id)->first();

                   if($cekdata == null) {
                $data->user_nama = "-";
              } else {
                $data->user_nama = $cekdata->nama_lengkap;
              }
      } else if($data->role_id == 6) {
          $cekdata = DB::table("kepala_sekolah")->where('user_id', $data->user_id)->first();

                   if($cekdata == null) {
                $data->user_nama = "-";
              } else {
                $data->user_nama = $cekdata->nama_lengkap;
              }
      } else if($data->role_id == 7) {
          $cekdata = DB::table("dinas_pendidikan")->where('user_id', $data->user_id)->first();
                   if($cekdata == null) {
                $data->user_nama = "-";
              } else {
                $data->user_nama = $cekdata->nama_lengkap;
              }
      }
    }
        return response()->json(["status" => 1, "data" => $datas]);
        }
    }catch(\Exception $e){
      return response()->json(["status" => 2, "message" => $e->getMessage()]);
    }
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
      $data->update(['user_id'=>$req->user_id,'perpus_kategori_id'=>$req->perpus_kategori_id,'pegawai_id'=>$req->pegawai_id,'judul'=>$req->judul,'author'=>$req->author,'bahasa'=>$req->bahasa,'total_halaman'=>$req->total_halaman,'foto'=>$imgPath]);
    } else {
      $data->update(['user_id'=>$req->user_id,'perpus_kategori_id'=>$req->perpus_kategori_id,'pegawai_id'=>$req->pegawai_id,'judul'=>$req->judul,'author'=>$req->author,'bahasa'=>$req->bahasa,'total_halaman'=>$req->total_halaman]);
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
