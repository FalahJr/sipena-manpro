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

class AbsensiPegawaiController extends Controller
{
    public static function getAbsensiPegawai()
    {
        $data = DB::table("pegawai_absensi")
            ->join("pegawai", "pegawai.id", '=', 'pegawai_absensi.pegawai_id')
            ->select("pegawai.*", "pegawai_absensi.*")
            ->get()->toArray();

        return $data;
    }

    public static function getMutasiPegawaiJson() {
      $data = AbsensiPegawaiController::getAbsensiPegawai();

      return response()->json($data);
    }

    public function index() {
      return view('absenpegawai.index');
    }

    public function datatable() {
      $data = AbsensiPegawaiController::getAbsensiPegawai();

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
          ->rawColumns(['terlambat', 'image', 'valid'])
          ->addIndexColumn()
          ->make(true);
    }

    public function simpan(Request $req) {
      $now = convertNameDayIdn(Carbon::now()->format('l'));

      $cekabsen = DB::table("pegawai_absensi")
                ->where("pegawai_id", $req->pegawai_id)
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

          $max = DB::table("pegawai_absensi")->max('id') + 1;

          $imgPath = null;
          $tgl = carbon::now('Asia/Jakarta');
          $folder = $tgl->year . $tgl->month . $tgl->timestamp;
          $dir = 'image/uploads/pegawai_absensi/' . $max;
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

          DB::table("pegawai_absensi")
              ->insert([
              "id" => $max,
              "pegawai_id" => $req->pegawai_id,
              "foto" => $imgPath,
              "waktu" => Carbon::now('Asia/Jakarta'),
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
          $dir = 'image/uploads/pegawai_absensi/' . $req->id;
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

          DB::table("pegawai_absensi")
              ->where('id', $req->id)
              ->update([
                "pegawai_id" => $req->pegawai_id,
                "foto" => $imgPath,
                "waktu" => Carbon::now('Asia/Jakarta'),
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
