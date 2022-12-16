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

class AbsensiGuruController extends Controller
{
    public static function getAbsensiGuru()
    {
        $data = DB::table("guru_absensi")
            ->join("guru", "guru.id", '=', 'guru_absensi.guru_id')
            ->select("guru.*", "guru_absensi.*", "guru_absensi.id as terlambat")
            ->get()->toArray();

            foreach ($data as $key => $value) {
              $waktu = Carbon::parse($value->waktu)->format('H:i:s');
              $batas = "06:00:00";

              if($waktu > Carbon::parse($batas)->format('H:i:s')) {
                $data->terlambat = "Y";
              } else {
                $data->terlambat = "N";
              }
            }

        return $data;
    }

    public static function getTotalKehadiran(Request $req) {
      $data = DB::table("guru_absensi")
          ->join("guru", "guru.id", '=', 'guru_absensi.guru_id')
          ->select("guru.*", "guru_absensi.*", "guru_absensi.id as terlambat")
          ->count();

        return response()->json($data);
    }

    public static function getMutasiGuruJson() {
      $data = AbsensiGuruController::getAbsensiGuru();

      return response()->json($data);
    }

    public function index() {
      return view('absenguru.index');
    }

    public function datatable() {
      $data = AbsensiGuruController::getAbsensiGuru();

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
          ->addColumn('waktu', function ($data) {
            return convertNameDayIdn(Carbon::parse($data->waktu)->format('l, d M Y H:i:s'));
          })
          ->addColumn('izin', function ($data) {
            if($data->is_izin === "Y") {
              return '<span class="badge badge-success"> Ya </span>';
            } else {
              return '<span class="badge badge-danger"> Tidak </span>';
            }
          })
          ->rawColumns(['terlambat', 'image', 'valid', 'izin'])
          ->addIndexColumn()
          ->make(true);
    }

    public function simpan(Request $req) {
      $now = convertNameDayIdn(Carbon::now()->format('l'));

      $cekabsen = DB::table("guru_absensi")
                ->where("guru_id", $req->pegawai_id)
                ->where('waktu', 'like', '%'.Carbon::now()->format('Y-m-d').'%')
                ->first();

      if($now == "Sabtu" || $now == "Minggu") {
        return response()->json(["status" => 7, "message" => "Sabtu & Minggu Libur!"]);
      } else if($cekabsen != null) {
        return response()->json(["status" => 7, "message" => "Guru sudah absen hari ini!"]);
      }

      if ($req->id == null) {
        DB::beginTransaction();
        try {

          $max = DB::table("guru_absensi")->max('id') + 1;

          $imgPath = null;
          $tgl = carbon::now('Asia/Jakarta');
          $folder = $tgl->year . $tgl->month . $tgl->timestamp;
          $dir = 'image/uploads/guru_absensi/' . $max;
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

          DB::table("guru_absensi")
              ->insert([
              "id" => $max,
              "guru_id" => $req->guru_id,
              "foto" => $imgPath,
              "waktu" => Carbon::now('Asia/Jakarta'),
            ]);

            if($req->is_izin) {
              DB::table("guru_absensi")
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
          $dir = 'image/uploads/guru_absensi/' . $req->id;
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

          DB::table("guru_absensi")
              ->where('id', $req->id)
              ->update([
                "guru_id" => $req->pegawai_id,
                "foto" => $imgPath,
                "waktu" => Carbon::now('Asia/Jakarta'),
            ]);

            if($req->is_izin) {
              DB::table("guru_absensi")
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
