<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\mMember;

use App\Authentication;

use Auth;

use Carbon\Carbon;

use Session;

use DB;

use File;

use Yajra\Datatables\Datatables;

class DompetDigitalController extends Controller
{
    public static function getDompetDigital($iduser = null)
    {
          if($iduser == null) {
            $data = DB::table("user")
                ->join("role", "role.id", '=', "user.role_id")
                ->select("user.*", "role.*", "user.id as id", "role.id as roleid", "role.nama as rolenama", "user.created_at as role", "user.created_at as nama_lengkap")
                ->get()->toArray();
          } else {
            $data = DB::table("user")
                ->join("role", "role.id", '=', "user.role_id")
                ->select("user.*", "role.*", "user.id as id", "role.id as roleid", "role.nama as rolenama", "user.created_at as role", "user.created_at as nama_lengkap")
                ->where("user.id", $iduser)
                ->get()->toArray();
          }

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

    public static function getDompetDigitalku($id)
    {
        $data = DB::table("user")
            ->join("role", "role.id", '=', "user.role_id")
            ->select("user.*", "role.*", "user.id as id", "role.id as roleid", "role.nama as rolenama", "user.created_at as role", "user.created_at as nama_lengkap")
            ->where("user.id", $id)
            ->first();

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

    public static function getDompetDigitalJson() {
      $data = DompetDigitalController::getDompetDigital();

      return response()->json($data);
    }

    public function index() {
      return view('dompetdigital.index');
    }

    public function indexsaya() {
      return view('dompetdigitalsaya.index');
    }

    public function digitalku() {
      return view('dompetdigital.digitalku');
    }

    public function datatable(Request $req) {
      $data = DompetDigitalController::getDompetDigital($req->id);

        return Datatables::of($data)
          ->addColumn('aksi', function ($data) {
            return  '<div class="btn-group">'.
                     '<button type="button" onclick="topup('.$data->id.')" class="btn btn-success btn-lg" title="topup">'.
                     '<label class="fa fa-plus"></label></button>'.
                  '</div>';
          })
          ->addColumn('saldo', function ($data) {
            return FormatRupiahFront($data->saldo);
          })
          ->rawColumns(['aksi'])
          ->addIndexColumn()
          ->make(true);
    }

    public function datatableku() {
      $data = DompetDigitalController::getDompetDigitalku();

        return Datatables::of($data)
          ->addColumn('aksi', function ($data) {
            return  '<div class="btn-group">'.
                     '<button type="button" onclick="topup('.$data->id.')" class="btn btn-success btn-lg" title="topup">'.
                     '<label class="fa fa-plus"></label></button>'.
                  '</div>';
          })
          ->addColumn('saldo', function ($data) {
            return FormatRupiahFront($data->saldo);
          })
          ->rawColumns(['aksi'])
          ->addIndexColumn()
          ->make(true);
    }

    public function topup(Request $req) {
        DB::beginTransaction();
        try {

          $max = DB::table("log_transaksi")->max('id') + 1;

          $imgPath = null;
          $tgl = carbon::now('Asia/Jakarta');
          $folder = $tgl->year . $tgl->month . $tgl->timestamp;
          $dir = 'image/uploads/dompet_digital/' . $max;
          $childPath = $dir . '/';
          $path = $childPath;

          $file = $req->file('image');
          $name = null;
          if ($file != null) {
              $this->deleteDir($dir);
              $name = $folder . '.' . $file->getClientOriginalExtension();
              if (!File::exists($path)) {
                  if (File::makeDirectory($path, 0777, true)) {
                    // compressImage($_FILES['image']['type'],$_FILES['image']['tmp_name'],$_FILES['image']['tmp_name'],50);
                      $file->move($path, $name);
                      $imgPath = $childPath . $name;
                  } else
                      $imgPath = null;
              } else {
                  return 'already exist';
              }
          }

          $nominal = str_replace("Rp. ","",$req->nominal);
          $nominal = str_replace(".","",$nominal);

          DB::table("log_transaksi")
              ->insert([
              "id" => $max,
              "user_id" => $req->id,
              "status" => "MASUK",
              "nominal" => $nominal,
              "keterangan" => $req->keterangan,
              "bukti_tf" => $imgPath,
              "created_date" => Carbon::now("Asia/Jakarta"),
            ]);

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
