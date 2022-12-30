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

class KehilanganBukuController extends Controller
{

  public function index()
  {
    $users = DB::table("user")->get();
    $books = DB::table("perpus_katalog")->get();
    return view('kehilangan_buku.index',compact('users','books'));
  }

  public function datatable()
  {
    $data = DB::table('perpus_kehilangan')
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
          '<a href="kehilangan-buku/edit/' . $data->id . '" class="btn btn-info btn-lg">'.
          '<label class="fa fa-pencil-alt"></label></a>' .
          '<a href="kehilangan-buku/hapus/'.$data->id.'" class="btn btn-danger btn-lg" title="hapus">' .
          '<label class="fa fa-trash"></label></a>' .
          '</div>';
      })->addColumn('buku', function ($data) {
        $katalog = DB::table("perpus_katalog")->where("id", $data->perpus_katalog_id)->first();
        $urlBook = '<a href="javascript:void(0)" data-id="'.$katalog->id.'" class="showBook" title="show">' . $katalog->judul .'</a><br>';
        return  $urlBook;
      })
      ->addColumn('nominal', function ($data) {
        return FormatRupiahFront($data->nominal);
      })
      ->addColumn('user', function ($data) {
        $user = DB::table("user")->where("id", $data->user_id)->first();
        return $user->username;

        // $user = DB::table("pegawai")->where("id", $data->user_id)->first()->nama_lengkap;
        // $user =+ DB::table("siswa")->where("id", $data->user_id)->first()->nama_lengkap;

      })
      ->rawColumns(['aksi', 'buku','user','nominal'])
      ->addIndexColumn()
      ->make(true);
  }

  public function show($id){
    $data = DB::table("perpus_katalog")->where("id",$id)->first();
    return response()->json(['data'=>$data]);
  }

  public function simpan(Request $req)
  {        
    $this->validate($req,[
          'perpus_katalog_id' => 'required|max:3',
    ]);
      try {
        
        if($req->user_id == null){
          $req->user_id = Auth::user()->id;
          $req->tanggal_pembayaran = date("Y-m-d");
        }
        $req->nominal = 50000;
        $user = DB::table("user")->where("id",$req->user_id);
        $sisaSaldo = $user->first()->saldo - $req->nominal;
        if($sisaSaldo<=0){
          return response()->json(["status" => 2, "message" => "saldo anda kurang"]);
        }else{
          $user->update(["saldo"=>$sisaSaldo]);
          $perpus = DB::table("perpustakaan");
          $saldoPerpus = $perpus->first()->saldo + $req->nominal;
          $perpus->update(['saldo'=>$saldoPerpus]);
        DB::table("perpus_kehilangan")
          ->insert([
            "user_id" => $req->user_id,
            "perpus_katalog_id" => $req->perpus_katalog_id,
            "nominal" => $req->nominal,
            "tanggal_pembayaran" => $req->tanggal_pembayaran,
          ]);
          DB::table('perpus_katalog')->where('id',$req->perpus_katalog_id)->decrement("stok_buku",1);
          DB::commit();


          return response()->json(["status" => 1, "message" => "berhasil membayar kehilangan buku"]);
        }
      } catch (\Exception $e) {
        DB::rollback();
        return response()->json(["status" => 7, "message" => "error ".$e]);
      }
  }

  public function APIinsertData(Request $req)
  {
      try {
        $req->tanggal_pembayaran = date("Y-m-d");
        $user = DB::table("user")->where("id",$req->user_id);
        $sisaSaldo = $user->first()->saldo - $req->nominal;
        if($sisaSaldo<=0){
          return response()->json(["status" => 2, "message" => "saldo anda kurang"]);
        }else{
          $user->update(["saldo"=>$sisaSaldo]);
          $perpus = DB::table("perpustakaan");
          $saldoPerpus = $perpus->first()->saldo + $req->nominal;
          $perpus->update(['saldo'=>$saldoPerpus]);

          DB::table("perpus_kehilangan")
          ->insert([
            "user_id" => $req->user_id,
            "perpus_katalog_id" => $req->perpus_katalog_id,
            "nominal" => $req->nominal,
            "tanggal_pembayaran" => $req->tanggal_pembayaran,
          ]);
          DB::table('perpus_katalog')->where('id',$req->perpus_katalog_id)->decrement("stok_buku",1);
          DB::commit();

        return response()->json(["status" => 1,"message" => "berhasil membayar denda kehilangan buku"]);
        }
      } catch (\Exception $e) {
        DB::rollback();
        return response()->json(["status" => 2, "message" => $e->getMessage()]);
      }
  }

  public function hapus($id)
  {
    DB::table("perpus_kehilangan")
        ->where('id',$id)
        ->delete();

      return back()->with(['success' => 'Data berhasil dihapus']);
  }

  public function edit($id)
  {
    $data = DB::table("perpus_kehilangan")->where("id", $id)->first();
    
    $users = DB::table("user")->get();
    $user_id = DB::table("user")->where("id",$data->user_id)->first()->id;
    $books = DB::table("perpus_katalog")->get();
    $book_id = DB::table("perpus_katalog")->where("id",$data->perpus_katalog_id)->first()->id;
    // dd($data);
    return view("kehilangan_buku.edit", compact('data','book_id','books','user_id','users'));
    
  }

  public function update(Request $req)
  {
    $this->validate($req,[
      'user_id' => 'required|max:255',
      'perpus_katalog_id' => 'required|max:255',
      'tanggal_pembayaran' => 'required|max:255',
    ]);
    DB::table("perpus_kehilangan")->where("id",$req->id)->update(['user_id'=>$req->user_id,'perpus_katalog_id'=>$req->perpus_katalog_id,'tanggal_pembayaran'=>$req->tanggal_pembayaran]);
    
    return back()->with(['success' => 'Data berhasil diupdate']);

    
  }



  public function getData(Request $req){
    try{
      if($req->id){
        $data = DB::table('perpus_kehilangan')
        ->join("user", "user.id", '=', 'perpus_kehilangan.user_id')
        ->join("role", "user.role_id", '=', 'role.id')
        ->join("perpus_katalog", "perpus_katalog.id", '=', 'perpus_kehilangan.perpus_katalog_id')
        ->join("perpus_kategori", "perpus_kategori.id", '=', 'perpus_katalog.perpus_kategori_id')
        ->select("perpus_kehilangan.*","perpus_katalog.judul","perpus_katalog.author","perpus_katalog.bahasa","perpus_katalog.total_halaman","perpus_kategori.nama as kategori_nama","user.role_id","role.nama as role_nama")
        ->when($req->id, function($q, $id) {
          return $q->where('perpus_kehilangan.id',$id);
        })
        ->first();
        if($data){
          if($data->role_id == 1) {
            $data->user_nama = "admin";
        } else if($data->role_id == 2) {
            $cekdata = DB::table("siswa")->where('user_id', $data->user_id)->first();
  
            $data->user_nama = $cekdata->nama_lengkap;
        } else if($data->role_id == 3) {
            $cekdata = DB::table("wali_murid")->where('user_id', $data->user_id)->first();
  
            $data->user_nama = $cekdata->nama_lengkap;
        } else if($data->role_id == 4) {
            $cekdata = DB::table("guru")->where('user_id', $data->user_id)->first();
  
            $data->user_nama = $cekdata->nama_lengkap;
        } else if($data->role_id == 5) {
            $cekdata = DB::table("pegawai")->where('user_id', $data->user_id)->first();
  
            $data->user_nama = $cekdata->nama_lengkap;
        } else if($data->role_id == 6) {
            $cekdata = DB::table("kepala_sekolah")->where('user_id', $data->user_id)->first();
  
            $data->user_nama = $cekdata->nama_lengkap;
        } else if($data->role_id == 7) {
            $cekdata = DB::table("dinas_pendidikan")->where('user_id', $data->user_id)->first();
            $data->user_nama = $cekdata->nama_lengkap;
        }
      }
        return response()->json(["status" => 1, "data" => $data]);
      }else{
        $datas = DB::table('perpus_kehilangan')
        ->join("user", "user.id", '=', 'perpus_kehilangan.user_id')
        ->join("role", "user.role_id", '=', 'role.id')
        ->join("perpus_katalog", "perpus_katalog.id", '=', 'perpus_kehilangan.perpus_katalog_id')
        ->join("perpus_kategori", "perpus_kategori.id", '=', 'perpus_katalog.perpus_kategori_id')
        ->select("perpus_kehilangan.*","perpus_katalog.judul","perpus_katalog.author","perpus_katalog.bahasa","perpus_katalog.total_halaman","perpus_kategori.nama as kategori_nama","user.role_id","role.nama as role_nama")
        ->get();
      foreach($datas as $data){
        if($data->role_id == 1) {
          $data->user_nama = "admin";
      } else if($data->role_id == 2) {
          $cekdata = DB::table("siswa")->where('user_id', $data->user_id)->first();

          $data->user_nama = $cekdata->nama_lengkap;
      } else if($data->role_id == 3) {
          $cekdata = DB::table("wali_murid")->where('user_id', $data->user_id)->first();

          $data->user_nama = $cekdata->nama_lengkap;
      } else if($data->role_id == 4) {
          $cekdata = DB::table("guru")->where('user_id', $data->user_id)->first();

          $data->user_nama = $cekdata->nama_lengkap;
      } else if($data->role_id == 5) {
          $cekdata = DB::table("pegawai")->where('user_id', $data->user_id)->first();

          $data->user_nama = $cekdata->nama_lengkap;
      } else if($data->role_id == 6) {
          $cekdata = DB::table("kepala_sekolah")->where('user_id', $data->user_id)->first();

          $data->user_nama = $cekdata->nama_lengkap;
      } else if($data->role_id == 7) {
          $cekdata = DB::table("dinas_pendidikan")->where('user_id', $data->user_id)->first();
          $data->user_nama = $cekdata->nama_lengkap;
      }
    }
        return response()->json(["status" => 1, "data" => $datas]);
        }
    }catch(\Exception $e){
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
