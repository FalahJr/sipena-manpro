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

class BayarKantinController extends Controller
{
  public function index()
  {
    $pegawai = DB::table('pegawai')->where("user_id", Auth::user()->id)->first();

    $items = DB::table('pegawai')->where('is_kantin','Y')->get();
    return view('kantin.index',compact('items','pegawai'));
  }

  public function datatable()
  {
    $data = DB::table('kantin')->get();
    $pegawai = DB::table('pegawai')->where("user_id", Auth::user()->id)->first();

    
    // return $data;
    // $xyzab = collect($data);
    // return $xyzab;
    // return $xyzab->i_price;
    return Datatables::of($data)
    //   ->addColumn("image", function ($data) {
    //     return '<div> <img src="' . url('/') . '/' . $data->profile_picture . '" style="height: 100px; width:100px; border-radius: 0px;" class="img-responsive"> </img> </div>';
    //   })
      ->addColumn('aksi', function ($data) {

          $full = '<div class="btn-group"><a href="bayar-kantin/edit/' . $data->id . '" class="btn btn-info btn-lg">'.
          '<label class="fa fa-pencil-alt"></label></a>' .
          '<a href="bayar-kantin/hapus/'.$data->id.'" class="btn btn-danger btn-lg" title="hapus">' .
          '<label class="fa fa-trash"></label></a>' .
          '<a href="bayar-kantin/'.$data->id.'" class="btn btn-success btn-lg" title="toBayar">Bayar Kantin</a>' .
          '</div>';
          $bayar = '<div class="btn-group"><a href="bayar-kantin/'.$data->id.'" class="btn btn-success btn-lg" title="toBayar">Bayar Kantin</a>' .
          '</div>';
          if(Auth::user()->role_id == 1 ){
            return $full;
          }else if(DB::table("pegawai")->where("user_id",Auth::user()->id)->where("is_kantin","Y")->get()->isNotEmpty()){
            if($data->pegawai_id == DB::table("pegawai")->where("user_id",Auth::user()->id)->where("is_kantin","Y")->first()->id){
              return $full;
            }else{
              return $bayar;
            }
          }else{
            return $bayar;
          }

      })->addColumn('foto', function ($data) {
        $url= asset($data->foto);
        return '<img src="' . $url . '" style="height: 80px; width:80px; border-radius: 0px;" class="img-responsive"> </img>';
      })
      ->addColumn('pegawai', function ($data) {
        $pegawai = DB::table('pegawai')->where('id',$data->pegawai_id)->first();
        return $pegawai->nama_lengkap;
      })
      ->addColumn('qr_code',function($data){
        return '<a href="#mymodal" data-remote="'.url('/admin/bayar-kantin/show/'.$data->id).'" data-toggle="modal" data-target="#mymodal" data-title="Show QRCode">'.QrCode::size(100)->generate($data->id).'</a>';
      })
      ->rawColumns(['aksi','foto','pegawai','qr_code'])
      ->addIndexColumn()
      ->make(true);
  }

  public function toBayar($id){
    $data = DB::table('kantin')->where("id",$id)->first();
    return view('kantin.pembayaran',compact("data"));
  }

  public function getData(Request $req){
    try{
      if($req->id){
        $data = DB::table('kantin')->where("id",$req->id)->first();
        return response()->json(["status" => 1, "data" => $data]);
      }else{
      $data = DB::table('kantin')->get();

        return response()->json(["status" => 1, "data" => $data]);

      }
    }catch(\Exception $e){
      return response()->json(["status" => 2, "message" => $e->getMessage()]);
    }
  }

  public function bayar(Request $req)
  {
    $user = DB::table('user')->where("id",$req->user_id)->first();
    if($user){
      if($user->role_id == 1){
        $user->user_nama = "admin";
      } else if($user->role_id == 2) {
          $cekdata = DB::table("siswa")->where('user_id', $user->id)->first();

          $user->user_nama = $cekdata->nama_lengkap;
      } else if($user->role_id == 3) {
          $cekdata = DB::table("wali_murid")->where('user_id', $user->id)->first();

          $user->user_nama = $cekdata->nama_lengkap;
      } else if($user->role_id == 4) {
          $cekdata = DB::table("guru")->where('user_id', $user->id)->first();

          $user->user_nama = $cekdata->nama_lengkap;
      } else if($user->role_id == 5) {
          $cekdata = DB::table("pegawai")->where('user_id', $user->id)->first();

          $user->user_nama = $cekdata->nama_lengkap;
      } else if($user->role_id == 6) {
          $cekdata = DB::table("kepala_sekolah")->where('user_id', $user->id)->first();

          $user->user_nama = $cekdata->nama_lengkap;
      } else if($user->role_id == 7) {
          $cekdata = DB::table("dinas_pendidikan")->where('user_id', $user->id)->first();
          $user->user_nama = $cekdata->nama_lengkap;
      }
    }

    $tgl = Carbon::now('Asia/Jakarta');
          if($user->role_id == 5){// jika role pegawai kantin maka pembayaran secara cash
            $pegawai = DB::table("pegawai")->where('user_id', $user->id)->first();
            if($pegawai->is_kantin == "Y"){
              DB::table("kantin_penjualan")
              ->insert([
                "kantin_id" => $req->kantin_id,
                "user_id" => $user->id,
                "nama_pembeli" => $user->user_nama,
                "keterangan" => $req->keterangan,
                "harga_total" => $req->harga_total,
                "tanggal_pembelian" => $tgl,
              ]);
              return back()->with(['success' => 'berhasil dibayar']);
            }
          }
          //jika role selain pegawai kurangin saldo user id
        $this->validate($req,[
          'keterangan' => 'required|max:255',
          'harga_total' => 'required|max:255',
        ]);
        $saldoUser = $user->saldo;
        $sisaSaldo = $saldoUser - $req->harga_total;
        if($sisaSaldo <= 0){
          return back()->with(['success' => 'Saldo kamu tidak mencukupi']);
        }else{
          DB::table("user")->where('id', $user->id)->update(['saldo'=>$sisaSaldo]);

          $kantin = DB::table("kantin")->where('id', $req->kantin_id);
          $saldoKantin = $kantin->first()->saldo + $req->harga_total;
          $kantin->update(['saldo'=>$saldoKantin]);

          DB::table("kantin_penjualan")
          ->insert([
            "kantin_id" => $req->kantin_id,
            "user_id" => $req->user_id,
            "nama_pembeli" => $user->user_nama,
            "keterangan" => $req->keterangan,
            "harga_total" => $req->harga_total,
            "tanggal_pembelian" => $tgl,
          ]);
          return back()->with(['success' => 'berhasil dibayar, sisa saldo anda Rp '.$sisaSaldo]);
        }
  }


  public function show($id)
  {
      return view('bayar_kantin.show',['id'=>$id]);
  }

  public function APIbayar(Request $req)
  {
    try{
      $user = DB::table('user')->where("id",$req->user_id)->first();
      if($user){
        if($user->role_id == 1){
          $user->user_nama = "admin";
        } else if($user->role_id == 2) {
          $cekdata = DB::table("siswa")->where('user_id', $user->id)->first();
          
          $user->user_nama = $cekdata->nama_lengkap;
        } else if($user->role_id == 3) {
          $cekdata = DB::table("wali_murid")->where('user_id', $user->id)->first();
          
          $user->user_nama = $cekdata->nama_lengkap;
        } else if($user->role_id == 4) {
          $cekdata = DB::table("guru")->where('user_id', $user->id)->first();

          $user->user_nama = $cekdata->nama_lengkap;
        } else if($user->role_id == 5) {
          $cekdata = DB::table("pegawai")->where('user_id', $user->id)->first();
          
          $user->user_nama = $cekdata->nama_lengkap;
        } else if($user->role_id == 6) {
          $cekdata = DB::table("kepala_sekolah")->where('user_id', $user->id)->first();
          
          $user->user_nama = $cekdata->nama_lengkap;
        } else if($user->role_id == 7) {
          $cekdata = DB::table("dinas_pendidikan")->where('user_id', $user->id)->first();
          $user->user_nama = $cekdata->nama_lengkap;
        }
      }

      $tgl = Carbon::now('Asia/Jakarta');
          if($user->role_id == 5){// jika role pegawai kantin maka pembayaran secara cash
            $pegawai = DB::table("pegawai")->where('user_id', $req->user_id)->first();
            if($pegawai->is_kantin == "Y"){
              DB::table("kantin_penjualan")
              ->insert([
                "kantin_id" => $req->kantin_id,
                "user_id" => $req->user_id,
                "nama_pembeli" => $user->user_nama,
                "keterangan" => $req->keterangan,
                "harga_total" => $req->harga_total,
                "tanggal_pembelian" => $tgl,
              ]);
              return response()->json(["status" => 2, "message" => "berhasil dibayar (cash)"]);
            }
          }
          //jika role selain pegawai kurangin saldo user id
        $saldoUser = $user->saldo;
        $sisaSaldo = $saldoUser - $req->harga_total;
        if($sisaSaldo <= 0){
          return response()->json(["status" => 2, "message" => "saldo kamu tidak mencukupi"]);
        }else{
          DB::table("user")->where('id', $user->id)->update(['saldo'=>$sisaSaldo]);

          $kantin = DB::table("kantin")->where('id', $req->kantin_id);
          $saldoKantin = $kantin->first()->saldo + $req->harga_total;
          $kantin->update(['saldo'=>$saldoKantin]);
          
          DB::table("kantin_penjualan")
          ->insert([
            "kantin_id" => $req->kantin_id,
            "user_id" => $req->user_id,
            "nama_pembeli" => $user->user_nama,
            "keterangan" => $req->keterangan,
            "harga_total" => $req->harga_total,
            "tanggal_pembelian" => $tgl,
          ]);
          return response()->json(["status" => 1, "message" => 'berhasil dibayar, sisa saldo anda Rp '.$sisaSaldo]);
        }
      }catch(\Exception $e){
        return response()->json(["status" => 2, "message" => $e->getMessage()]);
      }
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
        DB::table("kantin")
        ->insert([
          "id" => $max,
          "foto"=>$imgPath,
          "nama" => $req->nama,
          "pegawai_id" => $req->pegawai_id,
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
