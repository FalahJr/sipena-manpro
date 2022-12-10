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

class KartuDigitalController extends Controller
{
    public static function getKartuDigital()
    {
        $data = DB::table("siswa")
            ->join("kelas", "kelas.id", '=', 'siswa.kelas_id')
            ->select("siswa.*", "kelas.*", "siswa.id as id", "kelas.id as kelasid", "siswa.tanggal_daftar as linkGenerate")
            ->get()->toArray();

        foreach ($data as $key => $value) {
          $value->linkGenerate = url('/generatekartudigital?id=') . $value->id;
        }

        return $data;
    }

    public static function getKartuDigitalJson() {
      $data = KartuDigitalController::getKartuDigital();

      return response()->json($data);
    }

    public function index() {
      return view('kartu_digital.index');
    }

    public function datatable() {
      $data = KartuDigitalController::getKartuDigital();

        return Datatables::of($data)
          ->addColumn('aksi', function ($data) {
            return  '<div class="btn-group">'.
                     '<button type="button" onclick="lihatkartu('.$data->id.')" class="btn btn-info btn-lg" title="Lihat Kartu">'.
                     '<label class="fa fa-folder"></label></button>'.
                  '</div>';
          })
          ->rawColumns(['aksi'])
          ->addIndexColumn()
          ->make(true);
    }

    public function generate(Request $req) {
      $data = DB::table("siswa")
              ->where("siswa.id", $req->id)
              ->join("kelas", "kelas.id", '=', 'siswa.kelas_id')
              ->select("siswa.*", "kelas.*")
              ->first();

      return view('kartu_digital.cetakKartu', compact('data'));
    }

    public function generateJson(Request $req) {
      $data = DB::table("siswa")
              ->where("siswa.id", $req->id)
              ->join("kelas", "kelas.id", '=', 'siswa.kelas_id')
              ->select("siswa.*", "kelas.*", "siswa.id as id", "kelas.id as kelasid", "siswa.tanggal_daftar as linkGenerate")
              ->first();

      $data->linkGenerate = url('/generatekartudigital?id=') . $data->id;

      return response()->json($data);
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
