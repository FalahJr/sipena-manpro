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

class ListController extends Controller
{
  public function index()
  {
    $items = DB::table('pegawai')->where('is_kantin','Y')->get();
    return view('kantin.index',compact('items'));
  }

  public function datatable()
  {
    $data = DB::table('kantin')->get();


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
          '<a href="bayar-kantin/edit/' . $data->id . '" class="btn btn-info btn-lg">'.
          '<label class="fa fa-pencil-alt"></label></a>' .
          '<a href="/admin/bayar-kantin/hapus/'.$data->id.'" class="btn btn-danger btn-lg" title="hapus">' .
          '<label class="fa fa-trash"></label></a>' .
          '<a href="/admin/bayar-kantin/'.$data->id.'" class="btn btn-success btn-lg" title="toBayar">Bayar Kantin</a>' .
          '</div>';
      })->addColumn('foto', function ($data) {
        $url= asset($data->foto);
        return '<img src="' . $url . '" style="height: 80px; width:80px; border-radius: 0px;" class="img-responsive"> </img>';
      })
      ->addColumn('pegawai', function ($data) {
        $pegawai = DB::table('pegawai')->where('id',$data->pegawai_id)->first();
        return $pegawai->nama_lengkap;
      })
      ->addColumn('qr_code',function($data){
        $generateQRCode = QrCode::size(100)->generate($data->qr_code);
        return $generateQRCode;
      })
      ->rawColumns(['aksi','foto','pegawai','qr_code'])
      ->addIndexColumn()
      ->make(true);
  }

  public function toBayar($id){
    $data = DB::table('kantin')->where("id",$id)->first();
    return view('kantin.pembayaran',compact("data"));
  }

  public function bayar(Request $req)
  {
        $this->validate($req,[
          'nama_pembeli' => 'required|max:255',
          'keterangan' => 'required|max:255',
          'total_harga' => 'required|max:255',
        ]);
        $tgl = Carbon::now('Asia/Jakarta');
        DB::table("kantin_penjualan")
          ->insert([
            "kantin_id" => $req->kantin_id,
            "user_id" => $req->user_id,
            "nama_pembeli" => $req->nama_pembeli,
            "keterangan" => $req->keterangan,
            "harga_total" => $req->total_harga,
            "created_at" => $tgl,
          ]);
        
          return back()->with(['success' => 'Data berhasil diupdate']);
  }

  public function simpan(Request $req)
  {
      try {
        $imgPath = null;
        $tgl = Carbon::now('Asia/Jakarta');
        $folder = $tgl->year . $tgl->month . $tgl->timestamp;
        $childPath ='image/uploads/kantin/';
        $path = $childPath;

        $file = $req->file('foto');
        $name = null;
        if ($file != null) {
          $name = $folder . '.' . $file->getClientOriginalExtension();
          $file->move($path, $name);
          $imgPath = $childPath . $name;
        } else {
            return 'already exist';
        }

        $max = DB::table("kantin")->max('id') + 1;
        $linkCode = url('/kantin?id='.$max);
        DB::table("kantin")
        ->insert([
          "id" => $max,
          "foto"=>$imgPath,
          "nama" => $req->nama,
          "pegawai_id" => $req->pegawai_id,
          "qr_code" => $linkCode
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
    DB::table("kantin_penjualan")
    ->where('kantin_id',$id)
    ->delete();
    
    DB::table("kantin")
        ->where('id',$id)
        ->delete();

      return back()->with(['success' => 'Data berhasil dihapus']);
  }

  public function edit($id)
  {
    $data = DB::table("kantin")->where("id", $id)->first();
    $items = DB::table('pegawai')->get();
    $pegawai_id = DB::table('pegawai')->where("id",$data->pegawai_id)->first();
    return view("kantin.edit", compact('data','items','pegawai_id'));
  }

  public function update(Request $req)
  {
    $this->validate($req,[
      'nama' => 'required|max:255',
      'pegawai_id' => 'required|max:255',
    ]);
    $imgPath = null;
    $tgl = Carbon::now('Asia/Jakarta');
    $folder = $tgl->year . $tgl->month . $tgl->timestamp;
    $childPath ='image/uploads/kantin/';
    $path = $childPath;

    $file = $req->file('foto');
    $name = null;
    $data = DB::table("kantin")->where('id',$req->id);
    if ($file != null) {
      $name = $folder . '.' . $file->getClientOriginalExtension();
      $file->move($path, $name);
      $imgPath = $childPath . $name;
      $data->update(['nama'=>$req->nama,'pegawai_id'=>$req->pegawai_id,'foto'=>$imgPath]);
    } else {
      $data->update(['nama'=>$req->nama,'pegawai_id'=>$req->pegawai_id]);
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