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
    $employees = DB::table("pegawai")->where("is_perpus","Y")->get();
    $items = DB::table("perpus_katalog")->where('stok_buku','>=','1')->get();
    $users = DB::table("user")->get();
    return view('pinjam_buku.index',compact('items','employees','users'));
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
          $urlBook .= '<a href="javascript:void(0)" data-id="'.$katalog->id.'" class="showBook bg-secondary" title="show">' . $katalog->judul .'</a><br><br>';
        }

        return $urlBook;

      })
      ->addColumn('pegawai_id', function ($data) {
        if($data->pegawai_id){
        $employee = DB::table("pegawai")->where("id",$data->pegawai_id)->first()->nama_lengkap;
        return $employee;
      }else{
        return '<span class="badge badge-warning">'.
        'PENDING</span>';
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
        $this->validate($req,[
          'perpus_katalog_id' => 'required|max:3',
        ]);
        $max = DB::table("perpus_peminjaman")->max('id') + 1;
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

        return response()->json(["status" => 1]);
      } catch (\Exception $e) {
        DB::rollback();
        return response()->json(["status" => 7, "message" => "error"+$e]);
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

  public function getData(Request $req){
    try{
      if($req->id){
        $data = DB::table('perpus_peminjaman')
        ->join("perpus_peminjaman_katalog", "perpus_peminjaman_katalog.perpus_peminjaman_id", '=', 'perpus_peminjaman.id')
        ->join("perpus_katalog", "perpus_peminjaman_katalog.perpus_katalog_id", '=', 'perpus_katalog.id')
        ->select("perpus_peminjaman.*", "perpus_peminjaman_katalog.*","perpus_katalog.*")
        ->where("perpus_peminjaman.id",$req->id)->first();
      }
      // }else{
      //   $data = DB::table('perpus_peminjaman')
      //     ->join("siswa", "siswa.id", '=', 'perpus_peminjaman.siswa_id')
      //     ->join("mapel", "mapel.id", '=', 'perpus_peminjaman.mapel_id')
      //     ->join("kelas", "kelas.id", '=', 'perpus_peminjaman.kelas_id')
      //     ->select("perpus_peminjaman.*", "siswa.nama_lengkap as nama_siswa","mapel.nama as nama_mapel","kelas.nama as nama_kelas")
      //     ->when($req->kelas_id, function($q, $kelas_id) {
      //         return $q->where('perpus_peminjaman.kelas_id',$kelas_id);
      //     })
      //     ->when($req->mapel_id, function($q, $mapel_id) {
      //       return $q->where('perpus_peminjaman.mapel_id',$mapel_id);
      //     })
      //     ->when($req->siswa_id, function($q, $siswa_id) {
      //     return $q->where('perpus_peminjaman.siswa_id',$siswa_id);
      //     })
      //     ->when($req->semester, function($q, $semester) {
      //       return $q->where('perpus_peminjaman.semester',$semester);
      //     })
      //     ->when($req->is_show, function($q, $is_show) {
      //       return $q->where('perpus_peminjaman.is_show',$is_show);
      //     })
      //     ->get();
      // }
      return response()->json(["status" => 1, "data" => $data]);
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
    $employee_id = DB::table("pegawai")->where("id",$data->pegawai_id)->first()->id;
    $users = DB::table("user")->get();
    $user_id = DB::table("user")->where("id",$data->user_id)->first()->id;
    // dd($data);
    return view("pinjam_buku.edit", compact('data','books','items','employees','users','user_id','employee_id'));
    
  }

  public function update(Request $req)
  {
    $this->validate($req,[
      'user_id' => 'required|max:11',
      'pegawai_id' => 'required|max:11',
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

    DB::table("perpus_peminjaman")->where("id",$req->id)->update(['user_id'=>$req->user_id,'pegawai_id'=>$req->pegawai_id,'tanggal_peminjaman'=>$req->tanggal_peminjaman,'tanggal_pengembalian'=>$req->tanggal_pengembalian]);
    
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
