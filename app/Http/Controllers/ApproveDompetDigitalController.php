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

class ApproveDompetDigitalController extends Controller
{
    public static function getApproveDompetDigital()
    {
        $data = DB::table("log_transaksi")
            ->join("user", "user.id", '=', "log_transaksi.user_id")
            ->join("role", "role.id", '=', "user.role_id")
            ->select("user.*", "role.*", "user.id as id", "role.id as roleid", "role.nama as rolenama", "user.created_at as role", "user.created_at as nama_lengkap", "log_transaksi.*")
            ->get()->toArray();

            foreach ($data as $key => $value) {
              if($value->saldo == null) {
                $value->saldo = 0;
              }

              if($value->roleid == 1) {
                  $value->nama_lengkap = $value->username;
              } else if($value->roleid == 2) {
                  $cekdata = DB::table("siswa")->where('user_id', $value->id)->first();

                  if($cekdata == null) {
                    $value->nama_lengkap = "-";
                  } else {
                    $value->nama_lengkap = $cekdata->nama_lengkap;
                  }
              } else if($value->roleid == 3) {
                  $cekdata = DB::table("wali_murid")->where('user_id', $value->id)->first();

                  if($cekdata == null) {
                    $value->nama_lengkap = "-";
                  } else {
                    $value->nama_lengkap = $cekdata->nama_lengkap;
                  }
              } else if($value->roleid == 4) {
                  $cekdata = DB::table("guru")->where('user_id', $value->id)->first();

                  if($cekdata == null) {
                    $value->nama_lengkap = "-";
                  } else {
                    $value->nama_lengkap = $cekdata->nama_lengkap;
                  }
              } else if($value->roleid == 5) {
                  $cekdata = DB::table("pegawai")->where('user_id', $value->id)->first();

                  if($cekdata == null) {
                    $value->nama_lengkap = "-";
                  } else {
                    $value->nama_lengkap = $cekdata->nama_lengkap;
                  }
              } else if($value->roleid == 6) {
                  $cekdata = DB::table("kepala_sekolah")->where('user_id', $value->id)->first();

                  if($cekdata == null) {
                    $value->nama_lengkap = "-";
                  } else {
                    $value->nama_lengkap = $cekdata->nama_lengkap;
                  }
              } else if($value->roleid == 7) {
                  $cekdata = DB::table("dinas_pendidikan")->where('user_id', $value->id)->first();

                  if($cekdata == null) {
                    $value->nama_lengkap = "-";
                  } else {
                    $value->nama_lengkap = $cekdata->nama_lengkap;
                  }
              }
            }

        return $data;
    }

    public static function getApproveDompetDigitalJson() {
      $data = ApproveDompetDigitalController::getApproveDompetDigital();

      return response()->json($data);
    }

    public function index() {
      return view('approvedompetdigital.index');
    }

    public function datatable() {
      $data = ApproveDompetDigitalController::getApproveDompetDigital();

        return Datatables::of($data)
          ->addColumn('nominal', function ($data) {
            return FormatRupiahFront($data->nominal);
          })
          ->addColumn('aksi', function ($data) {
            if($data->is_approve == null) {
              return  '<div class="btn-group">'.
                       '<button type="button" onclick="tolak('.$data->id.')" class="btn btn-danger btn-lg" title="topup">'.
                       '<label class="fa fa-close"></label></button>'.
                       '&nbsp'.
                       '<button type="button" onclick="terima('.$data->id.')" class="btn btn-success btn-lg" title="topup">'.
                       '<label class="fa fa-check"></label></button>'.
                    '</div>';
            }
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
          ->addColumn("image", function($data) {
            return '<div> <img src="'.url('/') . '/' . $data->bukti_tf.'" style="height: 100px; width:100px; border-radius: 0px;" class="img-responsive"> </img> </div>';
          })
          ->rawColumns(['aksi', 'image', 'status'])
          ->addIndexColumn()
          ->make(true);
    }

    public function action(Request $req) {
        DB::beginTransaction();
        try {

          if($req->status == "approve") {
            $data = DB::table("log_transaksi")->where("id", $req->id)->first();
            $user = DB::table("user")->where("id", $data->user_id)->first();
            Notifikasi::push_notifikasi($data->user_id,"Berhasil isi saldo","Saldo dompet digital kamu sudah terisi");
            
            DB::table("log_transaksi")
                ->where("id", $req->id)
                ->update([
                    "is_approve" => "Y"
                  ]);

            DB::table("user")
                ->where("id", $data->user_id)
                ->update([
                  "saldo" => $data->nominal + $user->saldo
                ]);
          } else {
            $data = DB::table("log_transaksi")->where("id", $req->id)->first();
            Notifikasi::push_notifikasi($data->user_id,"Gagal Isi Saldo","Saldo dompet digital kamu tidak di acc oleh admin, harap teliti pastikan data telah sesuai");
            DB::table("log_transaksi")
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
