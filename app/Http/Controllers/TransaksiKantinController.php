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
      $kantin = DB::table("kantin")->where("pegawai_id",DB::table("pegawai")->where("user_id",Auth::user()->id)->first()->id)->first();
      $data = DB::table('kantin_transaksi')->where("kantin_id",$kantin->id)->get();
    }else if(Auth::user()->role_id == 1){
      $data = DB::table('kantin_transaksi')->get();
    }else{
      $data = DB::table('kantin_transaksi')->where("user_id",Auth::user()->id)->get();
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
          '<a href="/admin/transaksi-kantin/hapus/'.$data->id.'" class="btn btn-danger btn-lg" title="hapus">' .
          '<label class="fa fa-trash"></label></a>';
      })
      ->addColumn('tanggal_pembelian', function ($data) {
        return Carbon::CreateFromFormat('Y-m-d',$data->tanggal_pembelian)->format('d M Y');
      })
    ->addColumn('total_pembayaran', function ($data) {
        return FormatRupiahFront($data->total_pembayaran);
      })
      ->addColumn('kantin', function ($data) {
        $kantin = DB::table('kantin')->where('id',$data->kantin_id)->first();
        return $kantin->nama;
      })
      ->addColumn('user_id', function ($data) {
        $user = DB::table("user")->where("id",$data->user_id)->first();
        if($user){
          if($user->role_id == 1){
            $user->user_nama = "admin";
          } else if($user->role_id == 2) {
              $cekdata = DB::table("siswa")->where('user_id', $user->id)->first();
              if($cekdata == null) {
                $user->user_nama = "-";
              } else {
                $user->user_nama = $cekdata->nama_lengkap;
              }
          } else if($user->role_id == 3) {
              $cekdata = DB::table("wali_murid")->where('user_id', $user->id)->first();
    
                       if($cekdata == null) {
                $user->user_nama = "-";
              } else {
                $user->user_nama = $cekdata->nama_lengkap;
              }
          } else if($user->role_id == 4) {
              $cekdata = DB::table("guru")->where('user_id', $user->id)->first();
    
                       if($cekdata == null) {
                $user->user_nama = "-";
              } else {
                $user->user_nama = $cekdata->nama_lengkap;
              }
          } else if($user->role_id == 5) {
              $cekdata = DB::table("pegawai")->where('user_id', $user->id)->first();
    
                       if($cekdata == null) {
                $user->user_nama = "-";
              } else {
                $user->user_nama = $cekdata->nama_lengkap;
              }
          } else if($user->role_id == 6) {
              $cekdata = DB::table("kepala_sekolah")->where('user_id', $user->id)->first();
    
                       if($cekdata == null) {
                $user->user_nama = "-";
              } else {
                $user->user_nama = $cekdata->nama_lengkap;
              }
          } else if($user->role_id == 7) {
              $cekdata = DB::table("dinas_pendidikan")->where('user_id', $user->id)->first();
                       if($cekdata == null) {
                $user->user_nama = "-";
              } else {
                $user->user_nama = $cekdata->nama_lengkap;
              }
          }
        }
        return $user->user_nama;
      })
      ->addColumn('is_cash', function ($data) {
            if($data->is_cash == 'Y'){
              return "Cash";
            }else{
              return "Non-Cash";
            };
      })
      ->addColumn('pembelian', function ($data) {
          $items = DB::table("kantin_penjualan")->where('kantin_transaksi_id',$data->id)
          ->join("kantin_list", "kantin_list.id", '=', 'kantin_penjualan.kantin_list_id')
          ->select("kantin_list.nama","kantin_list.harga","kantin_penjualan.jumlah_pembelian","kantin_penjualan.total_harga")
          ->get();
          $penjualan = null;
          foreach($items as $item){
            $penjualan .= $item->nama .' || '. $item->harga .' x '. $item->jumlah_pembelian .' = '.$item->total_harga .'<br><br>';
          }
          return $penjualan;
      })
      ->rawColumns(['aksi','kantin','user_id',"pembelian","tanggal_pembelian","is_cash","total_pembayaran"])
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
    $data = DB::table("kantin_transaksi")->where("id", $id)->first();
    return view("transaksi_kantin.edit", compact('data'));
    
  }

  public function update(Request $req)
  {
    $this->validate($req,[
      'tanggal_pembelian' => 'required|max:255',
      'is_cash' => 'required|max:255',
    ]);
    // $imgPath = null;
    // $tgl = Carbon::now('Asia/Jakarta');
    // $folder = $tgl->year . $tgl->month . $tgl->timestamp;
    // $childPath ='image/uploads/kantin/kantin/';
    // $path = $childPath;

    // $file = $req->file('image');
    // $name = null;
    $data = DB::table("kantin_transaksi")->where('id',$req->id);
    // if ($file != null) {
    //   $name = $folder . '.' . $file->getClientOriginalExtension();
    //   $file->move($path, $name);
    //   $imgPath = $childPath . $name;
    //   $data->update(['judul'=>$req->judul,'deskripsi'=>$req->deskripsi,'id_kantin'=>$req->id_kantin,'foto'=>$imgPath]);
    // } else {
    //   $data->update(['judul'=>$req->judul,'deskripsi'=>$req->deskripsi,'id_kantin'=>$req->id_kantin]);
    // }
    $data->update(['tanggal_pembelian'=>$req->tanggal_pembelian,'is_cash'=>$req->is_cash]);
    // dd($data);
    return back()->with(['success' => 'Data berhasil diupdate']);
  }
  public function APIupdate(Request $req){
    try{
      if($req->kantin_transaksi_id){
        $data = DB::table("kantin_transaksi")->where("id",$req->kantin_transaksi_id);
        $data->update(['tanggal_pembelian'=>$req->tanggal_pembelian]);

        return response()->json(["status"=>1,"message"=>"berhasil diubah"]);
      }else{
        return response()->json(["status"=>2,"message"=>"tidak ditemukan"]);
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
        $data = DB::table("kantin_transaksi")->where("kantin_id",$req->kantin_id)->get(["id as kantin_transaksi_id","total_pembayaran","tanggal_pembelian"]);

        foreach($data as $value){
          $dataPenjualan = DB::table("kantin_penjualan")
          ->join("kantin_list","kantin_list.id","=","kantin_penjualan.kantin_list_id")
          ->select("kantin_penjualan.id","kantin_penjualan.jumlah_pembelian","kantin_list.nama","kantin_penjualan.total_harga")
          ->where("kantin_penjualan.kantin_transaksi_id",$value->kantin_transaksi_id)
          ->get();
          $value->list = $dataPenjualan;
        }
     

        return response()->json(["status"=>1,"data"=>$data]);
   
    }catch(\Exception $e){
      return response()->json(["status"=>2,"message"=>$e->getMessage()]);
    }
  }
  public function simpan(Request $req)
  {
      try {
        $i = 0;
        $totalPembayaran = 0;
        $transaksiId = DB::table("kantin_transaksi")->max('id') + 1;

        foreach($req->kantin_list_id as $kantin_list_id){
          $hargaPerItem = DB::table("kantin_list")->where("id",$kantin_list_id)->first();
          $jumlahPembelian = $req->jumlah_pembelian[$i]; 
          $totalHarga = $hargaPerItem->harga*$jumlahPembelian;
          $max = DB::table("kantin_penjualan")->max('id') + 1;
          DB::table("kantin_penjualan")
          ->insert([  
            "id" => $max,
            "jumlah_pembelian" => $jumlahPembelian,
            "kantin_transaksi_id" => $transaksiId,
            "kantin_list_id" => $kantin_list_id,
            "total_harga" => $totalHarga,
          ]);
          $totalPembayaran += $totalHarga;
          $i++;
        }
        // pembayaran cash ketika pegawai kantin isi pembayaran
        $pegawaiKantin = DB::table("pegawai")->where('user_id', Auth::user()->id)->where("is_kantin","Y")->first();
        if ($pegawaiKantin && $req->kantin_id == DB::table("kantin")->where("pegawai_id", $pegawaiKantin->id)->first()->id ){
          DB::table("kantin_transaksi")
          ->insert([
            "id" => $transaksiId,
            "kantin_id" => $req->kantin_id,
            "user_id" => Auth::user()->id,
            "total_pembayaran" => $totalPembayaran,
            "tanggal_pembelian" => date("Y-m-d"),
            "is_cash" => 'Y',
          ]);
          return back()->with(['success' => 'Data berhasil dihapus']);

        }

        // pembayara menggunakan saldo
        if($totalPembayaran < Auth::user()->saldo){
        DB::table("kantin_transaksi")
        ->insert([
          "id" => $transaksiId,
          "kantin_id" => $req->kantin_id,
          "user_id" => Auth::user()->id,
          "total_pembayaran" => $totalPembayaran,
          "tanggal_pembelian" => date("Y-m-d"),
          "is_cash" => 'N',
        ]);
        DB::table("kantin")->where("id",$req->kantin_id)->increment("saldo",$totalPembayaran);
        DB::table("user")->where("id",Auth::user()->id)->update(["saldo"=> Auth::user()->saldo - $totalPembayaran]);
        return back()->with(['success' => 'Berhasil dibayar']);

        }else{
          DB::table("kantin_penjualan")->where("kantin_transaksi_id",$transaksiId)->delete();
          return back()->with(['success' => 'Saldo anda tidak cukup']);
        }

      } catch (\Exception $e) {
        DB::rollback();
        return response()->json(["status" => 7, "message" => "error".$e->getMessage()]);
      }
  }

public function getMenu(Request $req){
  try{
    $data=DB::table("kantin_list")
    ->when($req->kantin_id,function($e,$kantin_id){
      $e->where("kantin_list.kantin_id",$kantin_id);
    })
    ->get();
    return response()->json(["status" => 1, "data" => $data]);
  }catch (\Exception $e) {
        DB::rollback();
        return response()->json(["status" => 2, "message" => $e->getMessage()]);
  }
}

  public function APIsimpan(Request $req)
  {
      try {
        
        $i = 0;
        $totalPembayaran = 0;
        $transaksiId = DB::table("kantin_transaksi")->max('id') + 1;

        foreach($req->kantin_list_id as $kantin_list_id){
          $hargaPerItem = DB::table("kantin_list")->where("id",$kantin_list_id)->first();
          $jumlahPembelian = $req->jumlah_pembelian[$i]; 
          $totalHarga = $hargaPerItem->harga*$jumlahPembelian;
          $max = DB::table("kantin_penjualan")->max('id') + 1;
          DB::table("kantin_penjualan")
          ->insert([  
            "id" => $max,
            "jumlah_pembelian" => $jumlahPembelian,
            "kantin_transaksi_id" => $transaksiId,
            "kantin_list_id" => $kantin_list_id,
            "total_harga" => $totalHarga,
          ]);
          $totalPembayaran += $totalHarga;
          $i++;
        }
        // pembayaran cash ketika pegawai kantin isi pembayaran
        $pegawaiKantin = DB::table("pegawai")->where('user_id', $req->user_id)->where("is_kantin","Y")->first();
        if ($pegawaiKantin && $req->kantin_id == DB::table("kantin")->where("pegawai_id", $pegawaiKantin->id)->first()->id ){
          DB::table("kantin_transaksi")
          ->insert([
            "id" => $transaksiId,
            "kantin_id" => $req->kantin_id,
            "user_id" => $req->user_id,
            "total_pembayaran" => $totalPembayaran,
            "tanggal_pembelian" => date("Y-m-d"),
            "is_cash" => 'Y',
          ]);
          return response()->json(["status" => 1,"message"=>"berhasil dibayar"]);
        }
        $user = DB::table("user")->where("id",$req->user_id)->first();
        // pembayara menggunakan saldo
        if($totalPembayaran > $user->saldo){
        DB::table("kantin_transaksi")
        ->insert([
          "id" => $transaksiId,
          "kantin_id" => $req->kantin_id,
          "user_id" => $req->user_id,
          "total_pembayaran" => $totalPembayaran,
          "tanggal_pembelian" => date("Y-m-d"),
          "is_cash" => 'N',
        ]);
        DB::table("user")->where("id",$user->id)->update(["saldo"=> $user->saldo - $totalPembayaran]);
        return response()->json(["status" => 1,"message"=>"berhasil dibayar"]);
        }else{
          DB::table("kantin_penjualan")->where("kantin_transaksi_id",$transaksiId)->delete();
          return response()->json(["status" => 2,"message"=>"saldo anda tidak cukup"]);
        }

      } catch (\Exception $e) {
        DB::rollback();
        return response()->json(["status" => 7, "message" => $e->getMessage()]);
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
