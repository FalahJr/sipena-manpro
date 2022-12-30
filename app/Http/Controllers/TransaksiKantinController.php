<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Account;

use App\Authentication;

use Auth;


use SimpleSoftwareIO\QrCode\Facades\QrCode;


use Carbon\Carbon;

use Session;

use DB;

use File;

use Yajra\Datatables\Datatables;

use Response;

class TransaksiKantinController extends Controller
{
  public function index()
  {
    return view('transaksi_kantin.index');
  }

  public function datatable()
  {

    $pegawaiKantin = DB::table("pegawai")->where("user_id",Auth::user()->id)->where("is_kantin","Y")->get()->isNotEmpty();
    if($pegawaiKantin){
      $kantin_id = DB::table("kantin")->where("pegawai_id",DB::table("pegawai")->where("user_id",Auth::user()->id)->first()->id)->first();
      $data = DB::table('kantin_penjualan')->where("kantin_id",$kantin_id->id)->get();
    }else if(Auth::user()->role_id == 1){
      $data = DB::table('kantin_penjualan')->get();
    }else{
      $data = DB::table('kantin_penjualan')->where("user_id",Auth::user()->id)->get();
    }

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
          '<a href="transaksi-kantin/edit/' . $data->id . '" class="btn btn-info btn-lg">'.
          '<label class="fa fa-pencil-alt"></label></a>' .
          '<a href="transaksi-kantin/hapus/'.$data->id.'" class="btn btn-danger btn-lg" title="hapus">' .
          '<label class="fa fa-trash"></label></a>';
      })
      ->addColumn('tanggal_pembelian', function ($data) {
        return Carbon::CreateFromFormat('Y-m-d',$data->tanggal_pembelian)->format('d M Y');
      })
      ->addColumn('kantin', function ($data) {
        $kantin = DB::table('kantin')->where('id',$data->kantin_id)->first();
        return $kantin->nama;
      })
      ->addColumn('pembayaran', function ($data) {
        $user = DB::table('user')->where('id',$data->user_id)->first();
        if($user){
          if($user->role_id == 5){
            $cekdata = DB::table("pegawai")->where('user_id', $data->user_id)->first();
            if($cekdata->is_kantin == "Y"){
              return "Cash";
            }else{
              return "Non-Cash";
            }
          }else{
            return "Cash";
          }
        }
      })
      ->rawColumns(['aksi','kantin',"tanggal_pembelian","pembayaran"])
      ->addIndexColumn()
      ->make(true);
  }

  public function hapus($id)
  {
    DB::table("kantin_penjualan")
        ->where('id',$id)
        ->delete();

      return back()->with(['success' => 'Data berhasil dihapus']);
  }

  public function edit($id)
  {
    $data = DB::table("kantin_penjualan")->where("id", $id)->first();
    $items = DB::table('kantin')->get();
    $kantin_id = DB::table('kantin')->where("id",$data->kantin_id)->first();
    return view("transaksi_kantin.edit", compact('data','items','kantin_id'));
    
  }

  public function update(Request $req)
  {
    $this->validate($req,[
      'keterangan' => 'required|max:255',
    ]);
    // $imgPath = null;
    // $tgl = Carbon::now('Asia/Jakarta');
    // $folder = $tgl->year . $tgl->month . $tgl->timestamp;
    // $childPath ='image/uploads/kantin/kantin/';
    // $path = $childPath;

    // $file = $req->file('image');
    // $name = null;
    $data = DB::table("kantin_penjualan")->where('id',$req->id);
    // if ($file != null) {
    //   $name = $folder . '.' . $file->getClientOriginalExtension();
    //   $file->move($path, $name);
    //   $imgPath = $childPath . $name;
    //   $data->update(['judul'=>$req->judul,'deskripsi'=>$req->deskripsi,'id_kantin'=>$req->id_kantin,'foto'=>$imgPath]);
    // } else {
    //   $data->update(['judul'=>$req->judul,'deskripsi'=>$req->deskripsi,'id_kantin'=>$req->id_kantin]);
    // }
    $data->update(['keterangan'=>$req->keterangan]);
    // dd($data);
    return back()->with(['success' => 'Data berhasil diupdate']);
  }
  public function APIupdate(Request $req){
    try{
      if($req->id){
        $data = DB::table("kantin_penjualan")->where("id",$req->id);
        $data->update(['keterangan'=>$req->keterangan]);

        return response()->json(["status"=>1,"message"=>"berhasil diubah"]);
      }else{
        return response()->json(["status"=>2,"message"=>"tidak ada param id"]);
      }

    }catch(\Exception $e){
      return response()->json(["status"=>2,"message"=>$e->getMessage()]);
    }
  }
  public function delete($id){
    try{
      if($id){
      DB::table("kantin_penjualan")->where("id",$id)->delete();
      return response()->json(["status"=>1,"message"=>"berhasil dihapus"]);
      }else{
        return response()->json(["status"=>1,"message"=>"parameter id tidak ditemukan"]);
      }
    }catch(\Exception $e){
      return response()->json(["status"=>2,"message"=>$e->getMessage()]);
    }
  }
  public function getData(Request $req){
    try{
      $data = DB::table("kantin_penjualan")
      ->when($req->kantin_id, function($q, $kantin_id) {
        return $q->where('kantin_penjualan.kantin_id',$kantin_id);
      })
      ->get();
      return response()->json(["status"=>1,"data"=>$data]);
    }catch(\Exception $e){
      return response()->json(["status"=>2,"message"=>$e->getMessage()]);
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
