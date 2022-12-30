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

class AbsensiKepalaSekolahController extends Controller
{
    public static function getAbsensiKepalaSekolah($userid = null)
    {
          if($userid == null) {
            $data = DB::table("kepala_sekolah_absensi")
              ->join("kepala_sekolah", "kepala_sekolah.id", '=', 'kepala_sekolah_absensi.kepala_sekolah_id')
              ->select("kepala_sekolah.*", "kepala_sekolah_absensi.*", "kepala_sekolah.id as terlambat")
              ->get()->toArray();
          } else {
            $user = DB::table("user")->select("user.*", "role.*", "user.id as id", "role.id as roleid", "role.nama as rolenama", "user.created_at as data", "user.created_at as role")->where("user.id", $userid)->join("role", "role.id", '=', "user.role_id")->first();
            if($user->roleid == 6) {
                $cekdata = DB::table("kepala_sekolah")->where('user_id', $user->id)->first();

                if($cekdata != null) {
                  $data = DB::table("kepala_sekolah_absensi")
                    ->join("kepala_sekolah", "kepala_sekolah.id", '=', 'kepala_sekolah_absensi.kepala_sekolah_id')
                    ->select("kepala_sekolah.*", "kepala_sekolah_absensi.*", "kepala_sekolah.id as terlambat")
                    ->where("kepala_sekolah.id", $cekdata->id)
                    ->get()->toArray();
                }
            }
          }

            foreach ($data as $key => $value) {
              $waktu = Carbon::parse($value->waktu)->format('H:i:s');
              $batas = "06:00:00";

              if($waktu > Carbon::parse($batas)->format('H:i:s')) {
                $value->terlambat = "Y";
              } else {
                $value->terlambat = "N";
              }
            }

        return $data;
    }

    public static function getTotalKehadiran(Request $req) {
        $data = DB::table("kepala_sekolah_absensi")
            ->join("kepala_sekolah", "kepala_sekolah.id", '=', 'kepala_sekolah_absensi.kepala_sekolah_id')
            ->select("kepala_sekolah.*", "kepala_sekolah_absensi.*")
            ->where("kepala_sekolah_absensi.kepala_sekolah_id", $req->id)
            ->count();

        return response()->json($data);
    }

    public static function getAbsensiKepalaSekolahJson(Request $req) {
      $data = AbsensiKepalaSekolahController::getAbsensiKepalaSekolah($req->userid);

      return response()->json($data);
    }

    public function index() {
      return view('absenkepalasekolah.index');
    }

    public function indexsaya() {
      return view('absenkepalasekolahsaya.index');
    }

    public function datatable(Request $req) {
      $data = AbsensiKepalaSekolahController::getAbsensiKepalaSekolah($req->id);

        return Datatables::of($data)
          ->addColumn("image", function($data) {
            return '<div> <img src="'.url('/') . '/' . $data->foto.'" style="height: 100px; width:100px; border-radius: 0px;" class="img-responsive"> </img> </div>';
          })
          ->addColumn('terlambat', function ($data) {
            $waktu = Carbon::parse($data->waktu)->format('H:i:s');
            $batas = "06:00:00";

            if($waktu > Carbon::parse($batas)->format('H:i:s')) {
              return '<span class="badge badge-danger"> Ya </span>';
            } else {
              return '<span class="badge badge-success"> Tidak </span>';
            }
          })
          ->addColumn('izin', function ($data) {
            if($data->is_izin === "Y") {
              return '<span class="badge badge-success"> Ya </span>';
            } else {
              return '<span class="badge badge-danger"> Tidak </span>';
            }
          })
          ->addColumn('waktu', function ($data) {
            return convertNameDayIdn(Carbon::parse($data->waktu)->format('l, d M Y H:i:s'));
          })
          ->rawColumns(['terlambat', 'image', 'valid', 'izin'])
          ->addIndexColumn()
          ->make(true);
    }

    public function simpan(Request $req) {
      $now = convertNameDayIdn(Carbon::now()->format('l'));

      $cekabsen = DB::table("kepala_sekolah_absensi")
                ->where("kepala_sekolah_id", $req->pegawai_id)
                ->where('waktu', 'like', '%'.Carbon::now()->format('Y-m-d').'%')
                ->first();

      if($now == "Sabtu" || $now == "Minggu") {
        return response()->json(["status" => 7, "message" => "Sabtu & Minggu Libur!"]);
      } else if($cekabsen != null) {
        return response()->json(["status" => 7, "message" => "Pegawai sudah absen hari ini!"]);
      }

      if ($req->id == null) {
        DB::beginTransaction();
        try {

          $max = DB::table("kepala_sekolah_absensi")->max('id') + 1;

          $imgPath = null;
          $tgl = carbon::now('Asia/Jakarta');
          $folder = $tgl->year . $tgl->month . $tgl->timestamp;
          $dir = 'image/uploads/kepala_sekolah_absensi/' . $max;
          $childPath = $dir . '/';
          $path = $childPath;

          $file = $req->file('foto');
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

          DB::table("kepala_sekolah_absensi")
              ->insert([
              "id" => $max,
              "kepala_sekolah_id" => $req->kepala_sekolah_id,
              "foto" => $imgPath,
              "waktu" => Carbon::now('Asia/Jakarta'),
            ]);

            if($req->is_izin) {
              DB::table("pegawai_absensi")
                ->where("id", $max)
                ->update([
                  "is_izin" => $req->is_izin,
                  "alasan_izin" => $req->alasan_izin,
                  "keterangan_izin" => $req->keterangan_izin
                ]);
            }

          DB::commit();
          return response()->json(["status" => 1]);
        } catch (\Exception $e) {
          DB::rollback();
          return response()->json(["status" => 2, "message" => $e->getMessage()]);
        }
      } else {
        DB::beginTransaction();
        try {

          $imgPath = null;
          $tgl = carbon::now('Asia/Jakarta');
          $folder = $tgl->year . $tgl->month . $tgl->timestamp;
          $dir = 'image/uploads/kepala_sekolah_absensi/' . $req->id;
          $childPath = $dir . '/';
          $path = $childPath;

          $file = $req->file('foto');
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

          DB::table("kepala_sekolah_absensi")
              ->where('id', $req->id)
              ->update([
                "kepala_sekolah_id" => $req->kepala_sekolah_id,
                "foto" => $imgPath,
                "waktu" => Carbon::now('Asia/Jakarta'),
            ]);

            if($req->is_izin) {
              DB::table("kepala_sekolah_absensi")
                ->where("id", $req->id)
                ->update([
                  "is_izin" => $req->is_izin,
                  "alasan_izin" => $req->alasan_izin,
                  "keterangan_izin" => $req->keterangan_izin
                ]);
            }

          DB::commit();
          return response()->json(["status" => 3]);
        } catch (\Exception $e) {
          DB::rollback();
          return response()->json(["status" => 4, "message" => $e->getMessage()]);
        }
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
