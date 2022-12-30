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

class KembaliBukuController extends Controller
{
  public function index()
  { 
    // ambil user yang meminjam buku
    $users = DB::table("perpus_peminjaman")
    ->where('is_kembali','N')
    ->whereNotNull('pegawai_id')
    ->join('user', 'perpus_peminjaman.user_id', '=', 'user.id')
    ->select('user.username','user.id')
    ->get();
    $employees = DB::table("pegawai")->where("is_perpus","Y")->get();
    return view('kembali_buku.index', compact('users','employees'));

  }

  public function datatable()
  {
    $full = DB::table('perpus_peminjaman')->whereNotNull("tanggal_dikembalikan")
      ->get();
    $byId = DB::table('perpus_peminjaman')->where("user_id",Auth::user()->id)
      ->whereNotNull("tanggal_dikembalikan")
      ->get();
    $data = Auth::user()->role_id == 1 || Auth::user()->role_id == 7|| DB::table("pegawai")->where("user_id",Auth::user()->id)->where("is_perpus","Y" )->orWhere("is_pengawas_sekolah","Y" )->get()->isNotEmpty() ? $full : $byId;
    // return $data;
    // $xyzab = collect($data);
    // return $xyzab;
    // return $xyzab->i_price;
      return Datatables::of($data)
      ->addColumn('aksi', function ($data) {
        return  '<div class="btn-group">' .
          '<a href="kembali-buku/edit/' . $data->id . '" class="btn btn-info btn-lg">'.
          '<label class="fa fa-pencil-alt"></label></a>' .
          '<a href="kembali-buku/hapus/'.$data->id.'" class="btn btn-danger btn-lg" title="hapus">' .
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
        if($data->is_acc == "Y"){
        $employee = DB::table("pegawai")->where("id",$data->pegawai_id)->first()->nama_lengkap;
        return $employee;
      }else{
        $pegawai = DB::table("pegawai")->where("user_id",Auth::user()->id)->where("is_perpus","Y")->first();
        if($pegawai){
          return '<a href="' . url('admin/kembali-buku/acc?pegawai_id='.$pegawai->id.'&id='.$data->id). '" class="badge badge-warning p-2 badge-lg">ACC Sekarang</a>';
        }else{
          return '<span class="badge badge-warning p-2 badge-lg">PROSES</span>';
        }
      }
      })
      ->addColumn('user', function ($data) {
        $user = DB::table("user")->where("id", $data->user_id)->first();
        return $user->username;
      })
      ->rawColumns(['aksi', 'buku','pegawai_id','user'])
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
        $peminjaman = DB::table("perpus_peminjaman")->where("user_id",$req->user_id)->first();
        
        DB::table("perpus_peminjaman")->where("user_id",$req->user_id)->update(['tanggal_dikembalikan'=>date("Y-m-d"),'is_kembali'=>'Y','pegawai_id'=>$req->pegawai_id]);

        $perpus_katalog = DB::table("perpus_peminjaman_katalog")
        ->where('perpus_peminjaman_id',$peminjaman->id)
        ->get();
    
        foreach($perpus_katalog as $katalog){
          DB::table("perpus_katalog")->where('id',$katalog->perpus_katalog_id)->increment('stok_buku',1);
        }

        DB::commit();

        return response()->json(["status" => 1]);
      } catch (\Exception $e) {
        DB::rollback();
        return response()->json(["status" => 7, "message" => $e]);
      }
  }

  public function getData(Request $req){
    try{
      if($req->id){
        if($req->user_id){
          $data = DB::table('perpus_peminjaman')
          ->join("user", "user.id", '=', 'perpus_peminjaman.user_id')
          ->join("role", "user.role_id", '=', 'role.id')
          ->select("perpus_peminjaman.id","perpus_peminjaman.user_id","perpus_peminjaman.pegawai_id","perpus_peminjaman.is_kembali","perpus_peminjaman.tanggal_peminjaman","perpus_peminjaman.tanggal_pengembalian","role.nama as nama_role","user.role_id")
          ->where("perpus_peminjaman.user_id",$req->user_id)
          ->where("perpus_peminjaman.is_kembali","N")        
          ->first();
        }else{
          $data = DB::table('perpus_peminjaman')
          ->join("user", "user.id", '=', 'perpus_peminjaman.user_id')
          ->join("role", "user.role_id", '=', 'role.id')
          ->select("perpus_peminjaman.id","perpus_peminjaman.user_id","perpus_peminjaman.pegawai_id","perpus_peminjaman.is_kembali","perpus_peminjaman.tanggal_peminjaman","perpus_peminjaman.tanggal_pengembalian","role.nama as nama_role","user.role_id")
          ->where("perpus_peminjaman.id",$req->id)
          ->where("perpus_peminjaman.is_kembali","Y")        
          ->first();
        }

        if($data){
          if($data->role_id == 1){
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
          $katalogs = DB::table("perpus_peminjaman_katalog")->where("perpus_peminjaman_id",$data->id)->get();
          $perpus_katalog = [];
          foreach($katalogs as $katalog){
            $perpus_katalog[] = DB::table("perpus_katalog")->where("id",$katalog->perpus_katalog_id)->get();
          }
          $data->jumlah_pinjam = count($perpus_katalog);
          $data->durasi = date_diff(date_create($data->tanggal_peminjaman),date_create(date("Y-m-d")))->days;
          if($data->durasi <= 7){
            $data->is_terlambat = "N";
            $data->total_denda = 0;
          }else{
            $data->is_terlambat = "Y";
            $data->total_denda = ($data->durasi - 7) * ($data->jumlah_pinjam * 1000);
          }
          $data->buku_pinjam = $perpus_katalog;
        }
        return response()->json(["status" => 1, "data" => $data]);
      }else{
        if($req->user_id){
          $datas = DB::table('perpus_peminjaman')
          ->join("user", "user.id", '=', 'perpus_peminjaman.user_id')
          ->join("role", "user.role_id", '=', 'role.id')
          ->select("perpus_peminjaman.id","perpus_peminjaman.user_id","perpus_peminjaman.pegawai_id","perpus_peminjaman.is_kembali","perpus_peminjaman.tanggal_peminjaman","perpus_peminjaman.tanggal_pengembalian","role.nama as nama_role","user.role_id")
          ->where("perpus_peminjaman.user_id",$req->user_id)
          ->where("perpus_peminjaman.is_kembali","N")        
          ->get();
        }else{
          $datas = DB::table('perpus_peminjaman')
          ->join("user", "user.id", '=', 'perpus_peminjaman.user_id')
          ->join("role", "user.role_id", '=', 'role.id')
          ->select("perpus_peminjaman.id","perpus_peminjaman.user_id","perpus_peminjaman.pegawai_id","perpus_peminjaman.is_kembali","perpus_peminjaman.tanggal_peminjaman","perpus_peminjaman.tanggal_pengembalian","role.nama as nama_role","user.role_id")
          ->where("perpus_peminjaman.is_kembali","Y")        
          ->get();
        }
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
        $katalogs = DB::table("perpus_peminjaman_katalog")->where("perpus_peminjaman_id",$data->id)->get();
          $perpus_katalog = [];
          foreach($katalogs as $katalog){
            $perpus_katalog[] = DB::table("perpus_katalog")->where("id",$katalog->perpus_katalog_id)->get();
          }
          $data->jumlah_pinjam = count($perpus_katalog);
          $data->durasi = date_diff(date_create($data->tanggal_peminjaman),date_create(date("Y-m-d")))->days;
          if($data->durasi <= 7){
            $data->is_terlambat = "N";
            $data->total_denda = 0;
          }else{
            $data->is_terlambat = "Y";
            $data->total_denda = ($data->durasi - 7) * ($data->jumlah_pinjam * 1000);
          }
          $data->buku_pinjam = $perpus_katalog;
      }
      return response()->json(["status" => 1, "data" => $datas]);
      }
    }catch(\Exception $e){
      return response()->json(["status" => 2, "message" => $e->getMessage()]);
    }
  }

  public function insertData(Request $req){
    try{
      if($req->total_denda == 'null'){
        return response()->json(["status" => 2, "message" => "tidak ada peminjaman buku"]);
      }
      if($req->total_denda>0){
        $peminjaman = DB::table("perpus_peminjaman")->where("user_id",$req->user_id)->whereNull('tanggal_dikembalikan')->first();

        $perpus_katalog = DB::table("perpus_peminjaman_katalog")
        ->where('perpus_peminjaman_id',$peminjaman->id)
        ->get();
    
        foreach($perpus_katalog as $katalog){
          DB::table("perpus_katalog")->where('id',$katalog->perpus_katalog_id)->increment('stok_buku',1);
        }

        $user = DB::table("user")->where("id",$req->user_id);
        $sisaSaldo = $user->first()->saldo - $req->total_denda;
        if($sisaSaldo<0){
          return response()->json(["status" => 2, "message" => "saldo anda kurang"]);
        }else{
          $user->update(["saldo"=>$sisaSaldo]);
          //kurangin saldo perpustakaan
          DB::table('perpus_peminjaman')
        ->join("user", "user.id", '=', 'perpus_peminjaman.user_id')
        ->join("role", "user.role_id", '=', 'role.id')
        ->select("perpus_peminjaman.id","perpus_peminjaman.user_id","perpus_peminjaman.pegawai_id","perpus_peminjaman.tanggal_peminjaman","perpus_peminjaman.tanggal_pengembalian","role.nama as nama_role","user.role_id")
        ->when($req->user_id,function($q,$user_id){
          return $q->where("perpus_peminjaman.user_id",$user_id);
        })
        ->update(["total_denda"=>$req->total_denda,"is_lunas"=>"Y","tanggal_dikembalikan"=>date("Y-m-d")]);
        }
      }else{
        $peminjaman = DB::table("perpus_peminjaman")->where("user_id",$req->user_id)->whereNull('tanggal_dikembalikan')->first();

        $perpus_katalog = DB::table("perpus_peminjaman_katalog")
        ->where('perpus_peminjaman_id',$peminjaman->id)
        ->get();
    
        foreach($perpus_katalog as $katalog){
          DB::table("perpus_katalog")->where('id',$katalog->perpus_katalog_id)->increment('stok_buku',1);
        }

        $user = DB::table("user")->where("id",$req->user_id);
        $sisaSaldo = $user->first()->saldo - $req->total_denda;
        if($sisaSaldo<0){
          return response()->json(["status" => 2, "message" => "saldo anda kurang"]);
        }else{
          $user->update(["saldo"=>$sisaSaldo]);

          $perpus = DB::table("perpustakaan");
          $saldoPerpus = $perpus->first()->saldo + $req->total_denda;
          $perpus->update(['saldo'=>$saldoPerpus]);
          
          //kurangin saldo perpustakaan
          DB::table('perpus_peminjaman')
        ->join("user", "user.id", '=', 'perpus_peminjaman.user_id')
        ->join("role", "user.role_id", '=', 'role.id')
        ->select("perpus_peminjaman.id","perpus_peminjaman.user_id","perpus_peminjaman.pegawai_id","perpus_peminjaman.tanggal_peminjaman","perpus_peminjaman.tanggal_pengembalian","role.nama as nama_role","user.role_id")
        ->when($req->user_id,function($q,$user_id){
          return $q->where("perpus_peminjaman.user_id",$user_id);
        })
        ->update(["total_denda"=>0,"is_lunas"=>"Y","tanggal_dikembalikan"=>date("Y-m-d")]);
        }
      }

        return response()->json(["status" => 1, "message" => "berhasil dikembalikan, menunggu acc pegawai"]);
      }catch(\Exception $e){
      return response()->json(["status" => 2, "message" => $e->getMessage()]);
    }
  }

  public function APIinsertData(Request $req){
    try{
      if($req->total_denda>0){
        $peminjaman = DB::table("perpus_peminjaman")->where("user_id",$req->user_id)->whereNull('tanggal_dikembalikan')->first();

        $perpus_katalog = DB::table("perpus_peminjaman_katalog")
        ->where('perpus_peminjaman_id',$peminjaman->id)
        ->get();
    
        foreach($perpus_katalog as $katalog){
          DB::table("perpus_katalog")->where('id',$katalog->perpus_katalog_id)->increment('stok_buku',1);
        }

        $user = DB::table("user")->where("id",$req->user_id);
        $sisaSaldo = $user->first()->saldo - $req->total_denda;
        if($sisaSaldo<0){
          return response()->json(["status" => 2, "message" => "saldo anda kurang"]);
        }else{
          $user->update(["saldo"=>$sisaSaldo]);
          //kurangin saldo perpustakaan
          DB::table('perpus_peminjaman')
        ->join("user", "user.id", '=', 'perpus_peminjaman.user_id')
        ->join("role", "user.role_id", '=', 'role.id')
        ->select("perpus_peminjaman.id","perpus_peminjaman.user_id","perpus_peminjaman.pegawai_id","perpus_peminjaman.tanggal_peminjaman","perpus_peminjaman.tanggal_pengembalian","role.nama as nama_role","user.role_id")
        ->when($req->user_id,function($q,$user_id){
          return $q->where("perpus_peminjaman.user_id",$user_id);
        })
        ->update(["total_denda"=>$req->total_denda,"is_lunas"=>"Y","tanggal_dikembalikan"=>date("Y-m-d")]);
        }
      }else{
        $peminjaman = DB::table("perpus_peminjaman")->where("user_id",$req->user_id)->whereNull('tanggal_dikembalikan')->first();

        $perpus_katalog = DB::table("perpus_peminjaman_katalog")
        ->where('perpus_peminjaman_id',$peminjaman->id)
        ->get();
    
        foreach($perpus_katalog as $katalog){
          DB::table("perpus_katalog")->where('id',$katalog->perpus_katalog_id)->increment('stok_buku',1);
        }

        $user = DB::table("user")->where("id",$req->user_id);
        $sisaSaldo = $user->first()->saldo - $req->total_denda;
        if($sisaSaldo<0){
          return response()->json(["status" => 2, "message" => "saldo anda kurang"]);
        }else{
          $user->update(["saldo"=>$sisaSaldo]);

          $perpus = DB::table("perpustakaan");
          $saldoPerpus = $perpus->first()->saldo + $req->total_denda;
          $perpus->update(['saldo'=>$saldoPerpus]);
          
          //kurangin saldo perpustakaan
          DB::table('perpus_peminjaman')
        ->join("user", "user.id", '=', 'perpus_peminjaman.user_id')
        ->join("role", "user.role_id", '=', 'role.id')
        ->select("perpus_peminjaman.id","perpus_peminjaman.user_id","perpus_peminjaman.pegawai_id","perpus_peminjaman.tanggal_peminjaman","perpus_peminjaman.tanggal_pengembalian","role.nama as nama_role","user.role_id")
        ->when($req->user_id,function($q,$user_id){
          return $q->where("perpus_peminjaman.user_id",$user_id);
        })
        ->update(["total_denda"=>0,"is_lunas"=>"Y","tanggal_dikembalikan"=>date("Y-m-d")]);
        }
      }

        return response()->json(["status" => 1, "message" => "berhasil dikembalikan, menunggu acc pegawai"]);
      }catch(\Exception $e){
      return response()->json(["status" => 2, "message" => $e->getMessage()]);
    }
  }

  public function APIaccKembali(Request $req){
    try{
      if($req->id == null && $req->pegawai_id == null){
        return response()->json(["status" => 2, "message" => "required peprus_sumbang.id and pegawai_id"]);
      }
        $data = DB::table('perpus_peminjaman')
        ->where("id",$req->id)->first();
        if($data){
          DB::table('perpus_peminjaman')->where("id",$req->id)->update(["pegawai_id"=>$req->pegawai_id,"is_kembali"=>"Y","is_acc"=>"Y"]);
          
          return response()->json(["status" => 1, "message" => "berhasil di acc"]);
          }else{
            return response()->json(["status" => 2, "message" => "id tidak ditemukan"]);
          }
        }catch(\Exception $e){
          return response()->json(["status" => 2, "message" => $e->getMessage()]);
        }
  }

  public function accKembali(Request $req){
    try{
        $data = DB::table('perpus_peminjaman')
        ->where("id",$req->id)->first();
        if($data){
          DB::table('perpus_peminjaman')->where("id",$req->id)->update(["pegawai_id"=>$req->pegawai_id,"is_kembali"=>"Y","is_acc"=>"Y"]);
          
          return back()->with(['success' => 'berhasil di acc']);
        }
    }catch(\Exception $e){
      return response()->json(["status" => 2, "message" => $e->getMessage()]);
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
    $employees = DB::table("pegawai")->where("is_perpus","Y")->get();
    $users = DB::table("user")->get();
    $user_id = DB::table("user")->where("id",$data->user_id)->first()->id;
    $items = DB::table("perpus_katalog")->get();
    // dd($data);
    return view("kembali_buku.edit", compact('data','books','items','employees','users','user_id'));
    
  }

  public function update(Request $req)
  {
    $this->validate($req,[
      'perpus_katalog_id' => 'required|max:3',
      'tanggal_peminjaman' => 'required|max:255',
      'tanggal_pengembalian' => 'required|max:255',
      'tanggal_dikembalikan' => 'required'
    ]);
    DB::table("perpus_peminjaman_katalog")->where('perpus_peminjaman_id',$req->id)->delete();
    foreach($req->perpus_katalog_id as $idKatalog){
      DB::table("perpus_peminjaman_katalog")->insert([
        "perpus_peminjaman_id" => $req->id,
        "perpus_katalog_id" => $idKatalog,
      ]);
    }

    DB::table("perpus_peminjaman")->where("id",$req->id)->update(['tanggal_peminjaman'=>$req->tanggal_peminjaman,'tanggal_pengembalian'=>$req->tanggal_pengembalian,
    'pegawai_id'=>$req->pegawai_id,'is_kembali'=>'Y','is_acc'=>'Y','tanggal_dikembalikan'=>$req->tanggal_dikembalikan]);

    
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
