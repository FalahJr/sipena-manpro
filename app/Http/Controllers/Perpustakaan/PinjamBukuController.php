<?php

namespace App\Http\Controllers\Perpustakaan;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\NotifikasiController as Notifikasi;
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
    $employees = DB::table("pegawai")->where("is_perpus","Y")->get();
    $items = DB::table("perpus_katalog")->where('stok_buku','>=','1')->get();
    $users = DB::table("user")->get();
    return view('pinjam_buku.index',compact('items','employees','users'));
  }

  public function datatable()
  {
    $byId = DB::table('perpus_peminjaman')->where("is_kembali","N")
    ->whereNull("tanggal_dikembalikan")
      ->where("user_id",Auth::user()->id)
      ->get();

    $full = DB::table('perpus_peminjaman')->where("is_kembali","N")
    ->whereNull("tanggal_dikembalikan")
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
          '<a href="pinjam-buku/edit/' . $data->id . '" class="btn btn-info btn-lg">'.
          '<label class="fa fa-pencil-alt"></label></a>' .
          '<a href="pinjam-buku/hapus/'.$data->id.'" class="btn btn-danger btn-lg" title="hapus">' .
          '<label class="fa fa-trash"></label></a>' .
          '</div>';
      })->addColumn('buku', function ($data) {
        $items = DB::table("perpus_peminjaman_katalog")->where("perpus_peminjaman_id", $data->id)->get();
        $urlBook = null;
        foreach($items as $item){
          $katalog = DB::table("perpus_katalog")->where("id", $item->perpus_katalog_id)->first();
          $urlBook .= '<a href="javascript:void(0)" data-id="'.$katalog->id.'" class="showBook bg-secondary" title="show">' . $katalog->judul .'</a><br><br>';
        }

        return $urlBook;

      })
    ->addColumn('pegawai_id', function ($data) {
    if($data->pegawai_id){
      $employee = DB::table("pegawai")->where("id",$data->pegawai_id)->first()->nama_lengkap;
      return $employee;
    }else{
      $pegawai = DB::table("pegawai")->where("user_id",Auth::user()->id)->where("is_perpus","Y")->first();
      if($pegawai){
        return '<a href="' . url('admin/pinjam-buku/acc?pegawai_id='.$pegawai->id.'&id='.$data->id). '" class="badge badge-warning p-2 badge-lg">ACC Sekarang</a>';
      }else{
        return '<span class="badge badge-warning p-2 badge-lg">PROSES</span>';
      }
    }
    })
      ->addColumn('user', function ($data) {
        $pegawai = DB::table("user")->where("id", $data->user_id)->first();
        return $pegawai->username;
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
        if($req->user_id == null && $req->pegawai_id == null){
          $req->user_id = Auth::user()->id;
          $req->pegawai_id = null;
        }
        $this->validate($req,[
          'perpus_katalog_id' => 'required|max:3',
        ]);
        $userPinjam = DB::table("perpus_peminjaman")->where("user_id",$req->user_id)->where("is_kembali","N")->first();
        if($userPinjam){
          return response()->json(["status" => 7, "message" => "untuk meminjam buku, user harus mengembalikan buku yang dipinjam sebelumnya"]);
        }
        $max = DB::table("perpus_peminjaman")->max('id') + 1;
        $date = Carbon::CreateFromFormat('Y-m-d', $req->tanggal_peminjaman);
        $req->tanggal_pengembalian = $date->addDays(7)->format('Y-m-d');
        DB::table("perpus_peminjaman")
          ->insert([
            "id" => $max,
            "user_id" => $req->user_id,
            "pegawai_id" => $req->pegawai_id,
            "tanggal_peminjaman" => $req->tanggal_peminjaman,
            "tanggal_pengembalian" => $req->tanggal_pengembalian,
          ]);
          foreach($req->perpus_katalog_id as  $perpus_katalog_id){
            DB::table("perpus_peminjaman_katalog")
            ->insert([
              "perpus_peminjaman_id" => $max,
              "perpus_katalog_id" => $perpus_katalog_id,
            ]);
            DB::table("perpus_katalog")->where('id',$perpus_katalog_id)->decrement('stok_buku');
          }
          DB::commit();

        return response()->json(["status" => 1,"message" => "berhasil dipinjamkan"]);
      } catch (\Exception $e) {
        DB::rollback();
        return response()->json(["status" => 2, "message" => $e]);
      }
  }
    

  public function insertData(Request $req){
      try {   
        $userPinjam = DB::table("perpus_peminjaman")->where("user_id",$req->user_id)->where("is_kembali","N")->first();
        if($userPinjam){
          return response()->json(["status" => 2, "message" => "untuk meminjam buku, user harus mengembalikan buku yang dipinjam sebelumnya"]);
        }
        $max = DB::table("perpus_peminjaman")->max('id') + 1;
        $date = Carbon::CreateFromFormat('Y-m-d', $req->tanggal_peminjaman);
        $req->tanggal_pengembalian = $date->addDays(7)->format('Y-m-d');
        DB::table("perpus_peminjaman")
          ->insert([
            "id" => $max,
            "user_id" => $req->user_id,
            "tanggal_peminjaman" => $req->tanggal_peminjaman,
            "tanggal_pengembalian" => $req->tanggal_pengembalian,
          ]);
          foreach($req->perpus_katalog_id as  $perpus_katalog_id){
            DB::table("perpus_peminjaman_katalog")
            ->insert([
              "perpus_peminjaman_id" => $max,
              "perpus_katalog_id" => $perpus_katalog_id,
            ]);
            DB::table("perpus_katalog")->where('id',$perpus_katalog_id)->decrement('stok_buku');
          }
          DB::commit();

        return response()->json(["status" => 1,"message" => "buku berhasil dipinjamkan, tunggu acc dari pegawai"]);
      } catch (\Exception $e) {
        DB::rollback();
        return response()->json(["status" => 2, "message" => $e->getMessage()]);
      }
  }

  public function getData(Request $req){
    try{
      if($req->id){
        $data = DB::table('perpus_peminjaman')
        ->join("user", "user.id", '=', 'perpus_peminjaman.user_id')
        ->join("role", "user.role_id", '=', 'role.id')
        ->select("perpus_peminjaman.id","perpus_peminjaman.user_id","perpus_peminjaman.pegawai_id","perpus_peminjaman.is_kembali","perpus_peminjaman.tanggal_peminjaman","perpus_peminjaman.tanggal_pengembalian","role.nama as nama_role","user.role_id")
        ->when($req->user_id,function($q,$user_id){
          return $q->where("perpus_peminjaman.user_id",$user_id);
        })
        ->when($req->is_kembali,function($q,$is_kembali){
          return $q->where("perpus_peminjaman.is_kembali",$is_kembali);
        })
        ->when($req->is_acc,function($q,$is_acc){
          return $q->where("perpus_peminjaman.is_acc",$is_acc);
        })
        ->where("perpus_peminjaman.id",$req->id)->first();
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
          $data->buku_pinjam = $perpus_katalog;
        }
        return response()->json(["status" => 1, "data" => $data]);
      }else{
        $datas = DB::table('perpus_peminjaman')
        ->join("user", "user.id", '=', 'perpus_peminjaman.user_id')
        ->join("role", "user.role_id", '=', 'role.id')
        ->select("perpus_peminjaman.id","perpus_peminjaman.user_id","perpus_peminjaman.pegawai_id","perpus_peminjaman.tanggal_dikembalikan","perpus_peminjaman.is_kembali","perpus_peminjaman.tanggal_peminjaman","perpus_peminjaman.tanggal_pengembalian","role.nama as nama_role","user.role_id")
        ->when($req->is_kembali,function($q,$is_kembali){
          return $q->where("perpus_peminjaman.is_kembali",$is_kembali);
        })
        ->when($req->is_acc,function($q,$is_acc){
          return $q->where("perpus_peminjaman.is_acc",$is_acc);
        })
        ->when($req->user_id,function($q,$user_id){
          return $q->where("perpus_peminjaman.user_id",$user_id);
        })
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
        $katalogs = DB::table("perpus_peminjaman_katalog")->where("perpus_peminjaman_id",$data->id)->get();
          $perpus_katalog = [];
          foreach($katalogs as $katalog){
            $perpus_katalog[] = DB::table("perpus_katalog")->where("id",$katalog->perpus_katalog_id)->get();
          }
          $data->jumlah_pinjam = count($perpus_katalog);
          $data->buku_pinjam = $perpus_katalog;
      }
      return response()->json(["status" => 1, "data" => $datas]);
      }
    }catch(\Exception $e){
      return response()->json(["status" => 2, "message" => $e->getMessage()]);
    }
  }

  public function APIaccPinjam(Request $req){
    try{
      if($req->id == null && $req->pegawai_id == null){
        return response()->json(["status" => 2, "message" => "required peprus_sumbang.id and pegawai_id"]);
      }
        $data = DB::table('perpus_peminjaman')
        ->where("id",$req->id)->first();
        if($data){
          DB::table('perpus_peminjaman')->where("id",$req->id)->update(["pegawai_id"=>$req->pegawai_id,"is_kembali"=>"N","is_acc"=>"Y"]);
          Notifikasi::push_notifikasi($data->user_id,"Pinjam Buku","Peminjaman buku berhasil di konfirmasi kamu bisa pergi ke perpus untuk pinjam buku");
          return response()->json(["status" => 1, "message" => "berhasil di acc"]);
        }else{
            return response()->json(["status" => 2, "message" => "id tidak ditemukan"]);
          }
        }catch(\Exception $e){
          return response()->json(["status" => 2, "message" => $e->getMessage()]);
        }
  }

  public function accPinjam(Request $req){
    try{
        $data = DB::table('perpus_peminjaman')
        ->where("id",$req->id)->first();
        if($data){
          DB::table('perpus_peminjaman')->where("id",$req->id)->update(["pegawai_id"=>$req->pegawai_id,"is_kembali"=>"N","is_acc"=>"Y"]);
          Notifikasi::push_notifikasi($data->user_id,"Pinjam Buku","Peminjaman buku berhasil di konfirmasi kamu bisa pergi ke perpus untuk pinjam buku");
          return back()->with(['success' => 'berhasil di acc']);
        }
        }catch(\Exception $e){
          return response()->json(["status" => 2, "message" => $e->getMessage()]);
        }
  }

  public function delete($id){
    try{
      if($id){
        $perpus_katalog = DB::table("perpus_peminjaman_katalog")
        ->where('perpus_peminjaman_id',$id)
        ->get();
    
        foreach($perpus_katalog as $katalog){
          DB::table("perpus_katalog")->where('id',$katalog->perpus_katalog_id)->increment('stok_buku',1);
        }
      
        DB::table("perpus_peminjaman_katalog")
            ->where('perpus_peminjaman_id',$id)
            ->delete();

            $user_id = DB::table("perpus_peminjaman")
            ->where('id',$id)->first()->user_id;
            Notifikasi::push_notifikasi($user_id,"Gagal Pinjam Buku","Peminjaman buku tidak diacc oleh pagawai perpus harap mencoba meminjam buku lain");

            DB::table("perpus_peminjaman")
            ->where('id',$id)
            ->delete();
            

        return response()->json(["status" => 1, "message" => "berhasil menghapus data"]);
      }else{
        return response()->json(["status" => 2, "message" => "id tidak ditemukan"]);
      }
    }catch(\Exception $e){
      return response()->json(["status" => 2, "message" => $e->getMessage()]);
    }
  }

  public function hapus($id)
  {

    $perpus_katalog = DB::table("perpus_peminjaman_katalog")
    ->where('perpus_peminjaman_id',$id)
    ->get();

    foreach($perpus_katalog as $katalog){
      DB::table("perpus_katalog")->where('id',$katalog->perpus_katalog_id)->increment('stok_buku',1);
    }
  
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
    $employees = DB::table("pegawai")->where("is_perpus","Y")->get();
    $users = DB::table("user")->get();
    $user_id = DB::table("user")->where("id",$data->user_id)->first()->id;
    // dd($data);
    return view("pinjam_buku.edit", compact('data','books','items','employees','users','user_id'));
    
  }

  public function update(Request $req)
  {
    $this->validate($req,[
      'user_id' => 'required|max:11',
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

    if($req->pegawai_id){

      DB::table("perpus_peminjaman")->where("id",$req->id)->update(['user_id'=>$req->user_id,'pegawai_id'=>$req->pegawai_id,'tanggal_peminjaman'=>$req->tanggal_peminjaman,'tanggal_pengembalian'=>$req->tanggal_pengembalian]);
    }else{

      DB::table("perpus_peminjaman")->where("id",$req->id)->update(['user_id'=>$req->user_id,'tanggal_peminjaman'=>$req->tanggal_peminjaman,'tanggal_pengembalian'=>$req->tanggal_pengembalian]);
    }

    
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
