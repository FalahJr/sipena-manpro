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
use LengthException;
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
          '<a href="transaksi-koperasi/edit/' . $data->id . '" class="btn btn-info btn-lg">'.
          '<label class="fa fa-pencil-alt"></label></a>' .
          '<a href="transaksi-koperasi/hapus/'.$data->id.'" class="btn btn-danger btn-lg" title="hapus">' .
          '<label class="fa fa-trash"></label></a>';
      })->addColumn('penjualan', function ($data) {
        $items = DB::table("koperasi_penjualan")->where('koperasi_transaksi_id',$data->id)
        ->join("koperasi_list", "koperasi_list.id", '=', 'koperasi_penjualan.koperasi_list_id')
        ->select("koperasi_list.nama","koperasi_list.harga","koperasi_penjualan.jumlah_pembelian","koperasi_penjualan.total_harga")
        ->get();
        $penjualan = null;
        foreach($items as $item){
          $penjualan .= $item->nama .' || '. $item->harga .' x '. $item->jumlah_pembelian .' = '.$item->total_harga .'<br><br>';
        }
        return $penjualan;
      })
      ->addColumn('tanggal', function ($data) {
        return Carbon::CreateFromFormat('Y-m-d',$data->tanggal)->format('d M Y');
      })
      ->addColumn('qr_code',function($data){
        return '<a href="#mymodal" data-remote="'.url('/admin/transaksi-koperasi/show/'.$data->id).'" data-toggle="modal" data-target="#mymodal" data-title="Show QRCode">'.QrCode::size(100)->generate($data->id).'</a>';
      })
      ->addColumn('is_lunas',function($data){
        if($data->is_lunas == "Y"){
          return "<span class='badge badge-success badge-lg'>LUNAS</span>";
        }else{
          return "<span class='badge badge-warning badge-lg'>PROSES</span>";
        }
      })
      ->addColumn('pegawai_id',function($data){
        $pegawai = DB::table("pegawai")->where("id",$data->pegawai_id)->first();
        return $pegawai->nama_lengkap;
      })
      ->rawColumns(['aksi','penjualan','qr_code','is_lunas','pegawai_id','tanggal'])
      ->addIndexColumn()
      ->make(true);
  }

  public function APIbayar(Request $req)
  {
    try{
          if($req->koperasi_transaksi_id && $req->user_id){// jika role pegawai kantin maka pembayaran secara cash
            $transaksi = DB::table("koperasi_transaksi")->where('id', $req->koperasi_transaksi_id);
            $user = DB::table("user")->where('id', $req->user_id);

            $saldoUser = $user->first()->saldo;
            $totalPembayaran = $transaksi->first()->total_pembayaran;
            $sisaSaldo = $saldoUser - $totalPembayaran;
            $transaksi->update(["is_lunas"=>"Y"]);

            if($sisaSaldo <= 0){
              return response()->json(["status" => 2, "message" => "saldo kamu tidak mencukupi"]);
            }else{
              $user->update(['saldo'=>$sisaSaldo]);

              $kantin = DB::table("koperasi");
              $saldoKantin = $kantin->first()->saldo + $totalPembayaran;
              $kantin->update(['saldo'=>$saldoKantin]);
              
              DB::table("koperasi_transaksi")->where('id', $req->koperasi_transaksi_id)->update(["user_id"=>$req->user_id]);

              return response()->json(["status" => 1, "message" => 'berhasil dibayar, sisa saldo anda Rp '.$sisaSaldo]);
            }
          }else{
            return response()->json(["status" => 2, "message" => "id user atau transaksi tidak ada"]);
          }
      }catch(\Exception $e){
        return response()->json(["status" => 2, "message" => $e->getMessage()]);
      }
  }

  public function show($id)
  {
      return view('transaksi_koperasi.show',['id'=>$id]);
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
            "total_harga" => $totalHarga,
          ]);
          array_push($penjualanId, $max);
          $totalPembayaran += $totalHarga;
          $i++;
        }

        DB::table("koperasi_transaksi")
        ->insert([
          "id" => $transaksiId,
          "pegawai_id" => $req->pegawai_id,
          "total_pembayaran" => $totalPembayaran,
          "tanggal" => date("Y-m-d"),
        ]);


          DB::commit();
        return response()->json(["status" => 1]);
      } catch (\Exception $e) {
        DB::rollback();
        return response()->json(["status" => 7, "message" => "error".$e]);
      }
  }

  public function getData(Request $req){
    try{
      if($req->id){
      $data = DB::table("koperasi_transaksi")->where("id",$req->id)->first(["id as koperasi_transaksi_id","total_pembayaran","tanggal"]);
      $dataPenjualan = DB::table("koperasi_penjualan")
      ->when($req->id, function($q, $transaksi_id) {
        return $q->where('koperasi_penjualan.koperasi_transaksi_id',$transaksi_id);
      })
      ->join("koperasi_list","koperasi_list.id","=","koperasi_penjualan.koperasi_list_id")
      ->select("koperasi_penjualan.id","koperasi_penjualan.jumlah_pembelian","koperasi_list.nama","koperasi_penjualan.total_harga")->get();
      $data->list = $dataPenjualan;
      return response()->json(["status"=>1,"data"=>$data]);
      }else{
        $data = DB::table("koperasi_transaksi")->get(["id as koperasi_transaksi_id","total_pembayaran","tanggal","is_lunas"]);
        return response()->json(["status"=>1,"data"=>$data]);
      }

    }catch(\Exception $e){
      return response()->json(["status"=>2,"message"=>$e->getMessage()]);
    }
  }

  public function delete($id){
    try{
      $data = DB::table("koperasi_penjualan")
      ->where('id',$id);
      if(!$data->first()){
        return response()->json(["status" => 2, "message" => "data tidak ditemukan"]);
      }
      
      DB::table("koperasi_transaksi")
      ->where('id',$data->first()->koperasi_transaksi_id)
      ->decrement("total_pembayaran",$data->first()->total_harga);

      $penjualan = DB::table("koperasi_penjualan")
      ->where('koperasi_transaksi_id',$data->first()->koperasi_transaksi_id)->count();

      if($penjualan == 1){
        DB::table("koperasi_transaksi")->where("id",$data->first()->koperasi_transaksi_id)->delete();
      }
      $data->delete();

    return response()->json(["status" => 1, "message" => "data berhasil dihapus"]);
  }catch(\Exception $e){
    return response()->json(["status" => 2, "message" => $e->getMessage()]);
  }
  }

  public function listPembelian(){
  try{
    $data = DB::table("koperasi_penjualan")
    ->join("koperasi_transaksi","koperasi_transaksi.id","=","koperasi_penjualan.koperasi_transaksi_id")
    ->join("koperasi_list","koperasi_list.id","=","koperasi_penjualan.koperasi_list_id")
    ->select("koperasi_penjualan.*","koperasi_transaksi.is_lunas","koperasi_list.nama as nama_barang","koperasi_list.harga as harga_barang")
    ->where("koperasi_transaksi.is_lunas","Y")
    ->get();
    return response()->json(["status"=>1,"data"=>$data]); 
  }catch(\Exception $e){
    return response()->json(["status"=>2,"message"=>$e->getMessage()]);
  }
  }

  public function hapus($id)
  {
        
    DB::table("koperasi_penjualan")
        ->where('koperasi_transaksi_id',$id)
        ->delete();

    DB::table("koperasi_transaksi")
    ->where('id',$id)
    ->delete();

      return back()->with(['success' => 'Data berhasil dihapus']);
  }

  public function edit($id)
  {
    $data = DB::table("koperasi_transaksi")->where("id", $id)->first();
    $employees = DB::table("pegawai")->where("is_koperasi","Y")->get();
    return view("transaksi_koperasi.edit", compact('data','employees'));
  }

  public function update(Request $req)
  {
    $this->validate($req,[
      'is_lunas' => 'required|max:255',
      'pegawai_id' => 'required|max:255',
    ]);
    $data = DB::table("koperasi_transaksi")->where('id',$req->id);
    $data->update(['is_lunas'=>$req->is_lunas,'pegawai_id'=>$req->pegawai_id]);

    // dd($data);
    return back()->with(['success' => 'Data berhasil diupdate']);
  }
  
  public function APIupdate(Request $req){
  try{
    $data = DB::table("koperasi_penjualan")->where("id",$req->id);
    if(!$data->first()){
      return response()->json(["status"=>2,"message"=>"data tidak ditemukan"]);
    }

    $req->harga_barang = DB::table("koperasi_penjualan")
    ->join("koperasi_list","koperasi_list.id","=","koperasi_penjualan.koperasi_list_id")
    ->select("koperasi_penjualan.*","koperasi_list.harga as harga_barang")
    ->where("koperasi_penjualan.id",$req->id)
    ->first()->harga_barang;

    $data->update([
      "koperasi_list_id"=>$req->koperasi_list_id,
      "jumlah_pembelian"=>$req->jumlah_pembelian,
      "total_harga"=>$req->jumlah_pembelian*$req->harga_barang,
    ]);
    $items = DB::table("koperasi_penjualan")->where("koperasi_transaksi_id",$data->first()->koperasi_transaksi_id)->get();
    $totalPembayaran = 0;
    foreach($items as $item){
      $totalPembayaran += $item->total_harga;
    }
    DB::table("koperasi_transaksi")->where("id",$data->first()->koperasi_transaksi_id)->update(["total_pembayaran"=>$totalPembayaran]);

    return response()->json(["status"=>1,"message"=>"berhasil diubah"]);
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
