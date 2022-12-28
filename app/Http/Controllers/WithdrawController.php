<?php

namespace App\Http\Controllers;
use App\Http\Controllers\NotifikasiController as Notifikasi;
use Illuminate\Http\Request;

use App\mMember;

use App\Authentication;

use Auth;

use Carbon\Carbon;

use Session;

use DB;

use File;

use Yajra\Datatables\Datatables;

class WithdrawController extends Controller
{
    public static function getWithdrawJson()
    {
        $datas = DB::table("withdraw")
            ->join("user", "user.id", '=', "withdraw.user_id")
            ->join("role", "role.id", '=', "user.role_id")
            ->select("withdraw.*","role.nama as nama_role","user.role_id","role.nama as rolenama")
            ->get();
            
            foreach($datas as $data){
              
              if($data->role_id == 1) {
                $data->nama_lengkap = "admin";
              } else if($data->role_id == 2) {
                $cekdata = DB::table("siswa")->where('user_id', $data->user_id)->first();
                                  if($cekdata == null) {
                    $data->nama_lengkap = "-";
                  } else {
                $data->nama_lengkap = $cekdata->nama_lengkap;
                  }
            } else if($data->role_id == 3) {
                $cekdata = DB::table("wali_murid")->where('user_id', $data->user_id)->first();
                        if($cekdata == null) {
                    $data->nama_lengkap = "-";
                  } else {
                $data->nama_lengkap = $cekdata->nama_lengkap;
                  }
            } else if($data->role_id == 4) {
                $cekdata = DB::table("guru")->where('user_id', $data->user_id)->first();
                        if($cekdata == null) {
                    $data->nama_lengkap = "-";
                  } else {
                $data->nama_lengkap = $cekdata->nama_lengkap;
                  }
            } else if($data->role_id == 5) {
                $cekdata = DB::table("pegawai")->where('user_id', $data->user_id)->first();
                        if($cekdata == null) {
                    $data->nama_lengkap = "-";
                  } else {
                $data->nama_lengkap = $cekdata->nama_lengkap;
                  }

            } else if($data->role_id == 6) {
                $cekdata = DB::table("kepala_sekolah")->where('user_id', $data->user_id)->first();
                                  if($cekdata == null) {
                    $data->nama_lengkap = "-";
                  } else {
                $data->nama_lengkap = $cekdata->nama_lengkap;
                  }
            } else if($data->role_id == 7) {
                $cekdata = DB::table("dinas_pendidikan")->where('user_id', $data->user_id)->first();
                                  if($cekdata == null) {
                    $data->nama_lengkap = "-";
                  } else {
                $data->nama_lengkap = $cekdata->nama_lengkap;
                  }
            }

          }

        return $datas;
    }

    public function APIinsertData(Request $req){
      try{
        DB::table("withdraw")->insert([
          "user_id" => $req->user_id,
          "saldo" => $req->saldo,
          "keterangan" => $req->keterangan,
          "nominal" => $req->nominal,
        ]);
        return response()->json(["status"=>1,"message"=>"Berhasil diajukan tunggu notifikasi approve penarikan saldo dan cek rekening kamu"]);
      }catch(\Exception $e){
        return response()->json(["status"=>2,"message"=>$e->getMessage()]);

      }
    }

    public function insertData(Request $req){
      try{
        $req->saldo = DB::table("kantin")->where("pegawai_id",DB::table("pegawai")->where("user_id",Auth::user()->id)->first()->id)->first()->saldo;
        $req->user_id = Auth::user()->id;
        DB::table("withdraw")->insert([
          "user_id" => $req->user_id,
          "saldo" => $req->saldo,
          "keterangan" => $req->keterangan,
          "nominal" => $req->nominal,
        ]);
        return response()->json(["status"=>1,"message"=>"Berhasil diajukan tunggu notifikasi approve penarikan saldo dan cek rekening kamu"]);
      }catch(\Exception $e){
        return response()->json(["status"=>2,"message"=>$e->getMessage()]);

      }
    }

    public static function getData() {
      $data = WithdrawController::getWithdrawJson();

      return response()->json(["status"=>1,"data"=>$data]);
    }

    public function index() {
      return view('withdraw.index');
    }

    public function datatable() {
      $data = WithdrawController::getWithdrawJson();

        return Datatables::of($data)
          ->addColumn('nominal', function ($data) {
            return FormatRupiahFront($data->nominal);
          })
          ->addColumn('aksi', function ($data) {
              return  '<div class="btn-group">'.
                       '<button type="button" onclick="tolak('.$data->id.')" class="btn btn-danger btn-lg" title="Tolak">'.
                       '<label class="fa fa-close"></label></button>'.
                       '&nbsp'.
                       '<button type="button" onclick="terima('.$data->id.')" class="btn btn-success btn-lg" title="Setujui">'.
                       '<label class="fa fa-check"></label></button>'.
                    '</div>';
          })
          ->addColumn('status', function ($data) {
            if($data->is_approve == "Y") {
              return '<span class="badge badge-success"> Diterima </span>';
            } else if($data->is_approve == "N") {
              return '<span class="badge badge-danger"> Ditolak </span>';
            } else {
              return '<span class="badge badge-warning"> Belum Diproses </span>';
            }
          })
          ->rawColumns(['aksi', 'status','aksi'])
          ->addIndexColumn()
          ->make(true);
    }

    public function action(Request $req) {
        DB::beginTransaction();
        try {

          if($req->status == "approve") {
            $data = DB::table("withdraw")->where("id", $req->id)->first();
            Notifikasi::push_notifikasi($data->user_id,"Berhasil Withdraw","Admin sudah transfer silahkan cek rekening anda");
            
            DB::table("withdraw")
                ->where("id", $req->id)
                ->update([
                    "is_approve" => "Y"
                  ]);
                
            $pegawai_id = DB::table("pegawai")->where("user_id",$data->user_id)->first()->id;
            $saldo_kantin = DB::table("kantin")
                ->where("pegawai_id",$pegawai_id)->first()->saldo;

            DB::table("kantin")
                ->where("pegawai_id",$pegawai_id)
                ->update(["saldo"=>$saldo_kantin - $data->nominal]);
          } else {
            $data = DB::table("withdraw")->where("id", $req->id)->first();
            Notifikasi::push_notifikasi($data->user_id,"Gagal Withdraw","Penarikan uang tidak di konfirmasi oleh admin, harap teliti pastikan data telah sesuai");
            DB::table("withdraw")
                ->where("id", $req->id)
                ->update([
                  "is_approve" => "N"
                ]);
          }

          DB::commit();
          return response()->json(["status" => 1]);
        } catch (\Exception $e) {
          DB::rollback();
          return response()->json(["status" => 2, "message" => $e->getMessage()]);
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
