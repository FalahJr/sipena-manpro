<?php

namespace App\Http\Controllers;

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

class BeritaKelasController extends Controller
{
  public function index()
  {
    $items = DB::table('kelas')->get();
    return view('berita_kelas.index',compact('items'));
  }
  public function show($id){
    DB::table("berita")->where("id",$id)->increment("total_views",1);
    $data = DB::table("berita")
    ->join("kelas","kelas.id","=","berita.kelas_id")
    ->select("berita.*","kelas.nama as namaKelas")
    ->where("berita.id",$id)
    ->first();
    return response()->json(['data'=>$data]);
  }
  public function datatable()
  {
    $data = DB::table('berita')->whereNotNull('kelas_id')
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
        $showBerita = '<div class="btn-group">' .
        '<a href="javascript:void(0)" data-id="'.$data->id.'" class="showDetail btn btn-secondary btn-lg" title="detail berita"><label class="fa fa-eye"></label></a>'.
        '</div>';
        $full = '<div class="btn-group">' .
        '<a href="berita-kelas/edit/' . $data->id . '" class="btn btn-info btn-lg">'.
        '<label class="fa fa-pencil-alt"></label></a>' .
        '<a href="berita-kelas/hapus/'.$data->id.'" class="btn btn-danger btn-lg" title="hapus">' .
        '<label class="fa fa-trash"></label></a>' .
        '<a href="javascript:void(0)" data-id="'.$data->id.'" class="showDetail btn btn-secondary btn-lg" title="detail berita"><label class="fa fa-eye"></label></a>'.
        '</div>';
        return Auth::user()->role_id == 1 || DB::table('siswa')->where("user_id",Auth::user()->id)->where("is_osis","Y")->get()->isNotEmpty() ? $full : $showBerita;
        
      })->addColumn('foto', function ($data) {
        $url= asset($data->foto);
        return '<img src="' . $url . '" style="height: 80px; width:80px; border-radius: 0px;" class="img-responsive"> </img>';
      })
      ->addColumn('deskripsi',function($data){
        return substr($data->deskripsi,0,80).'...';
      })
      ->addColumn('kelas', function ($data) {
        $kelas = DB::table('kelas')->where('id',$data->kelas_id)->first();
        return $kelas->nama;
      })
      ->rawColumns(['aksi', 'foto','kelas','deskripsi'])
      ->addIndexColumn()
      ->make(true);
  }

  public function simpan(Request $req)
  {
      try {
        $imgPath = null;
        $tgl = Carbon::now('Asia/Jakarta');
        $folder = $tgl->year . $tgl->month . $tgl->timestamp;
        $childPath ='image/uploads/berita/kelas/';
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

        DB::table("berita")
          ->insert([
            "judul" => $req->judul,
            "deskripsi" => $req->deskripsi,
            "foto" => $imgPath,
            "total_views" => '0',
            "kelas_id" => $req->kelas_id,
            "tanggal" => Carbon::now('Asia/Jakarta'),
          ]);
          DB::commit();

        return response()->json(["status" => 1]);
      } catch (\Exception $e) {
        DB::rollback();
        return response()->json(["status" => 7, "message" => $e]);
      }
  }

  public function hapus($id)
  {
    DB::table("berita")
        ->where('id',$id)
        ->delete();

      return back()->with(['success' => 'Data berhasil dihapus']);
  }

  public function getData(Request $req){
    try{
      if($req->id){
        DB::table("berita")->where("id",$req->id)->increment("total_views",1);
        if($req->kategori == "kelas"){
          $data = DB::table('berita')
          ->join("kelas","kelas.id","=","berita.kelas_id")
          ->select("berita.*","kelas.nama as kelas_nama")
          ->where("berita.id",$req->id)
          ->whereNotNull('kelas_id')
          ->first();
        }else{
          $data = DB::table('berita')->where("id",$req->id)
          ->whereNull('kelas_id')
          ->first();
        }
        return response()->json(["status" => 1, "data"=>$data]);
      }

      if($req->kategori == "kelas"){
        $data = DB::table('berita')
        ->join("kelas","kelas.id","=","berita.kelas_id")
        ->select("berita.*","kelas.nama as kelas_nama")
        ->whereNotNull('kelas_id')
        ->get();
        if($req->kelas_id){
          $data = DB::table('berita')
          ->join("kelas","kelas.id","=","berita.kelas_id")
          ->select("berita.*","kelas.nama as kelas_nama")
          ->whereNotNull('kelas_id')
          ->where('kelas_id',$req->kelas_id)
          ->get();
        }
      }else if($req->kategori == "sekolah"){
        $data = DB::table('berita')->whereNull('kelas_id')
        ->get();
      }else{
        return response()->json(["status" => 2, "message"=>"kategori berita tidak ditemukan"]);
      }

      if(!$req->kategori){
        $data = DB::table('berita')->get();
      }

      return response()->json(["status" => 1, "data"=>$data]);

    } catch (\Exception $e) {
      DB::rollback();
      return response()->json(["status" => 2, "message" => $e->getMessage()]);
    }
  }

  public function edit($id)
  {
    $data = DB::table("berita")->where("id", $id)->first();
    $items = DB::table('kelas')->get();
    $noKelas = DB::table('kelas')->where("id",$data->kelas_id)->first();
    return view("berita_kelas.edit", compact('data','items','noKelas'));
    
  }

  public function update(Request $req)
  {
    $this->validate($req,[
      'judul' => 'required|max:255',
    ]);
    $imgPath = null;
    $tgl = Carbon::now('Asia/Jakarta');
    $folder = $tgl->year . $tgl->month . $tgl->timestamp;
    $childPath ='image/uploads/berita/kelas/';
    $path = $childPath;

    $file = $req->file('image');
    $name = null;
    $data = DB::table("berita")->where('id',$req->id);
    if ($file != null) {
      $name = $folder . '.' . $file->getClientOriginalExtension();
      $file->move($path, $name);
      $imgPath = $childPath . $name;
      $data->update(['judul'=>$req->judul,'deskripsi'=>$req->deskripsi,'kelas_id'=>$req->kelas_id,'foto'=>$imgPath]);
    } else {
      $data->update(['judul'=>$req->judul,'deskripsi'=>$req->deskripsi,'kelas_id'=>$req->kelas_id]);
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
