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

class AbsensiSiswaController extends Controller
{
    public static function getAbsensiSiswa()
    {
        $data = DB::table("siswa_absensi")
            ->join("siswa", "siswa.id", '=', 'siswa_absensi.siswa_id')
            ->join("jadwal_pembelajaran", "jadwal_pembelajaran.id", '=', 'siswa_absensi.jadwal_pembelajaran_id')
            ->join("mapel", "mapel.id", '=', 'jadwal_pembelajaran.mapel_id')
            ->join("kelas", "kelas.id", '=', 'jadwal_pembelajaran.kelas_id')
            ->select("siswa.*", "siswa_absensi.*", "jadwal_pembelajaran.*", "mapel.*", "kelas.*", "siswa_absensi.id as id", "siswa.id as siswaid",  "mapel.id as mapelid", "mapel.nama as mapelnama", "kelas.id as kelasid", "kelas.nama as kelasnama", "siswa_absensi.created_at")
            ->get()->toArray();

        return $data;
    }

    public static function getTotalKehadiran(Request $req) {
        $data = DB::table("siswa_absensi")
            ->join("siswa", "siswa.id", '=', 'siswa_absensi.siswa_id')
            ->join("jadwal_pembelajaran", "jadwal_pembelajaran.id", '=', 'siswa_absensi.jadwal_pembelajaran_id')
            ->join("mapel", "mapel.id", '=', 'jadwal_pembelajaran.mapel_id')
            ->join("kelas", "kelas.id", '=', 'jadwal_pembelajaran.kelas_id')
            ->select("siswa.*", "siswa_absensi.*", "jadwal_pembelajaran.*", "mapel.*", "kelas.*", "siswa_absensi.id as id", "siswa.id as siswaid",  "mapel.id as mapelid", "mapel.nama as mapelnama", "kelas.id as kelasid", "kelas.nama as kelasnama", "siswa_absensi.created_at")
            ->where("siswa_absensi.siswa_id", $req->id)
            ->get()->toArray();

            $count = 0;
            foreach ($data as $key => $value) {
              $date2 = convertNameDayIdn(Carbon::parse($value->created_at)->format('l'));

              if($value->jadwal_hari === $date2) {
                $count += 1;
              }
            }

        return response()->json($count);
    }

    public static function getMutasiSiswaJson() {
      $data = AbsensiSiswaController::getAbsensiSiswa();

      return response()->json($data);
    }

    public function index() {
      return view('absensisiswa.index');
    }

    public function datatable() {
      $data = AbsensiSiswaController::getAbsensiSiswa();

        return Datatables::of($data)
          ->addColumn("image", function($data) {
            return '<div> <img src="'.url('/') . '/' . $data->foto.'" style="height: 100px; width:100px; border-radius: 0px;" class="img-responsive"> </img> </div>';
          })
          ->addColumn('terlambat', function ($data) {
            $date2 = convertNameDayIdn(Carbon::parse($data->created_at)->format('l'));

            $created_at = Carbon::parse($data->created_at)->format('H:i:s');

            if($data->jadwal_hari === $date2) {
              if($created_at > $data->jadwal_waktu) {
                return '<span class="fa fa-check"> </span>';
              } else {
                return '<span class="fa fa-close"> </span>';
              }
            } else {
              return '<span class="fa fa-close"> </span>';
            }
          })
          ->addColumn('valid', function ($data) {
            $date2 = convertNameDayIdn(Carbon::parse($data->created_at)->format('l'));

            if($data->jadwal_hari === $date2) {
              return '<span class="badge badge-success"> Ya </span>';
            } else {
              return '<span class="badge badge-danger"> Tidak </span>';
            }
          })
          ->addColumn('approve', function ($data) {
            if($data->is_approve === "Y") {
              return '<span class="badge badge-success"> Ya </span>';
            } else {
              return '<span class="badge badge-danger"> Tidak </span>';
            }
          })
          ->addColumn('jadwal', function ($data) {
            return $data->jadwal_hari . ", " . $data->jadwal_waktu;
          })
          ->addColumn('created_at', function ($data) {
            return convertNameDayIdn(Carbon::parse($data->created_at)->format('l, d M Y H:i:s'));
          })
          ->rawColumns(['terlambat', 'image', 'valid', 'approve'])
          ->addIndexColumn()
          ->make(true);
    }

    public function simpan(Request $req) {
      $cek = DB::table("jadwal_pembelajaran")
                ->where("id", $req->jadwal_pembelajaran_id)
                ->first();

      $cekabsen = DB::table("siswa_absensi")
                ->where("siswa_id", $req->siswa_id)
                ->where('created_at', 'like', '%'.Carbon::now()->format('Y-m-d').'%')
                ->first();

      $now = convertNameDayIdn(Carbon::now()->format('l'));

      if($cek->jadwal_hari != $now) {
        return response()->json(["status" => 7, "message" => "Ketika absen harus sama dengan jadwal pembelajaran!"]);
      } else if($cekabsen != null) {
        return response()->json(["status" => 7, "message" => "Siswa sudah absen hari ini!"]);
      }

      if ($req->id == null) {
        DB::beginTransaction();
        try {

          $max = DB::table("siswa_absensi")->max('id') + 1;

          $imgPath = null;
          $tgl = carbon::now('Asia/Jakarta');
          $folder = $tgl->year . $tgl->month . $tgl->timestamp;
          $dir = 'image/uploads/siswa_absensi/' . $max;
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

          DB::table("siswa_absensi")
              ->insert([
              "id" => $max,
              "jadwal_pembelajaran_id" => $req->jadwal_pembelajaran_id,
              "siswa_id" => $req->siswa_id,
              "foto" => $imgPath,
              "created_at" => Carbon::now('Asia/Jakarta'),
            ]);

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
          $dir = 'image/uploads/siswa_absensi/' . $req->id;
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

          DB::table("siswa_absensi")
              ->where('id', $req->id)
              ->update([
              "jadwal_pembelajaran_id" => $req->jadwal_pembelajaran_id,
              "siswa_id" => $req->siswa_id,
              "foto" => $imgPath,
              "created_at" => Carbon::now('Asia/Jakarta'),
            ]);

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
