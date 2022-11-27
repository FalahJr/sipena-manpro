<?php

namespace App\Http\Controllers\Koperasi;
use App\Http\Controllers\Controller;
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

class TransaksiController extends Controller
{
  public function index()
  {
    $employees = DB::table("pegawai")->where("is_koperasi","Y")->get();
    $cooperatives = DB::table('koperasi_list')->get();
    return view('transaksi_koperasi.index',compact('cooperatives','employees'));
  }

  public function datatable()
  {
    $data = DB::table('koperasi_transaksi')->get();


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
          '<label class="fa fa-trash"></label></a>';
      })->addColumn('penjualan', function ($data) {
        $items = DB::table("koperasi_penjualan")->where('koperasi_transaksi_id',$data->id)
        ->join("koperasi_list", "koperasi_list.id", '=', 'koperasi_penjualan.koperasi_list_id')
        ->select("koperasi_list.nama","koperasi_list.harga","koperasi_penjualan.jumlah_pembelian","koperasi_penjualan.total_harga")
        ->get();
        $penjualan = null;
        foreach($items as $item){
          $penjualan .= $item->nama .' || '. $item->harga .' x '. $item->jumlah_pembelian .' = '.$item->total_harga .'<br>';
        }
        return $penjualan;
      })
      ->addColumn('qr_code',function($data){
        $generateQRCode = QrCode::size(100)->generate($data->qr_code);
        return $generateQRCode;
      })
      ->addColumn('is_lunas',function($data){
        if($data->is_lunas == "Y"){
          return "SUCCESS";
        }else{
          return "PENDING";
        }
      })
      ->addColumn('pegawai_id',function($data){
        $pegawai = DB::table("pegawai")->where("id",$data->pegawai_id)->first();
        return $pegawai->nama_lengkap;
      })
      ->rawColumns(['aksi','penjualan','qr_code','is_lunas','pegawai_id'])
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
        DB::table("koperasi_list")
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
        $i = 0;
        $penjualanId = array();
        $totalPembayaran = 0;
        $transaksiId = DB::table("koperasi_transaksi")->max('id') + 1;

        foreach($req->koperasi_list_id as $koperasi_list_id){
          $hargaPerItem = DB::table("koperasi_list")->where("id",$koperasi_list_id)->first();
          $jumlahPembelian = $req->jumlah_pembelian[$i]; 
          $totalHarga = $hargaPerItem->harga*$jumlahPembelian;
          $max = DB::table("koperasi_penjualan")->max('id') + 1;
          DB::table("koperasi_penjualan")
          ->insert([  
            "id" => $max,
            "jumlah_pembelian" => $jumlahPembelian,
            "koperasi_transaksi_id" => $transaksiId,
            "koperasi_list_id" => $koperasi_list_id,
            "jumlah_pembelian" => $jumlahPembelian,
            "total_harga" => $totalHarga,
          ]);
          array_push($penjualanId, $max);
          $totalPembayaran += $totalHarga;
          $i++;
        }

        DB::table("koperasi_transaksi")
        ->insert([
          "id" => $transaksiId,
          "qr_code" => '/koperasi/show/'.$transaksiId,
          "pegawai_id" => $req->pegawai_id,
          "total_pembayaran" => $totalPembayaran,
        ]);


          DB::commit();
        return response()->json(["status" => 1]);
      } catch (\Exception $e) {
        DB::rollback();
        return response()->json(["status" => 7, "message" => "error".$e]);
      }
  }

  public function hapus($id)
  {
    DB::table("koperasi_list")
    ->where('kantin_id',$id)
    ->delete();
    
    DB::table("koperasi_list")
        ->where('id',$id)
        ->delete();

      return back()->with(['success' => 'Data berhasil dihapus']);
  }

  public function edit($id)
  {
    $data = DB::table("koperasi_list")->where("id", $id)->first();
    $items = DB::table('pegawai')->where("is_koperasi","Y")->get();
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
    $data = DB::table("koperasi_list")->where('id',$req->id);
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
