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

class KeuanganController extends Controller
{
    public static function getKeuangan()
    {
        $data = DB::table("keuangan")
        ->join("keuangan_kategori", "keuangan_kategori.id", '=', 'keuangan.keuangan_kategori_id')
        ->join("siswa", "siswa.id", '=', 'keuangan.siswa_id')
        ->join("kelas", "kelas.id", '=', 'siswa.kelas_id')
        ->select("siswa.*", "keuangan.*", "keuangan_kategori.*", "keuangan_kategori.nama as ketegorinama", "kelas.nama as kelasnama")
        ->get()->toArray();

        return $data;
    }

    public static function getKeuanganJson() {
      $data = KeuanganController::getKeuangan();

      return response()->json($data);
    }

    public function index() {
      $data2 = DB::table("keuangan")
                ->get();
      $kategori = DB::table("keuangan_kategori")->get();
      $siswa = DB::table("siswa")->get();


      return view('data-keuangan.index', compact('data2', 'kategori', 'siswa'));
    }

    public function datatable() {
      $data = KeuanganController::getKeuangan();

        return Datatables::of($data)
        ->addColumn("siswa_id", function ($data) {
          $siswa = DB::table('siswa')->where('id', $data->siswa_id)->first();

          return $siswa->nama_lengkap ;
        })
        ->addColumn("keuangan_kategori_id", function ($data) {
          $keuangan_kategori = DB::table('keuangan_kategori')->where('id', $data->keuangan_kategori_id)->first();

          return $keuangan_kategori->nama ;
        })
        ->addColumn('nominal', function ($data) {
          return FormatRupiahFront($data->nominal);
        })
          ->addColumn('aksi', function ($data) {
            return  '<div class="btn-group">'.
                     '<button type="button" onclick="edit('.$data->id.')" class="btn btn-info btn-lg" title="edit">'.
                     '<label class="fa fa-pencil-alt"></label></button>'.
                     '<button type="button" onclick="hapus('.$data->id.')" class="btn btn-danger btn-lg" title="hapus">'.
                     '<label class="fa fa-trash"></label></button>'.
                  '</div>';
          })
          ->rawColumns(['aksi'])
          ->addIndexColumn()
          ->make(true);
    }

    public function simpan(Request $req) {
      if ($req->id == null) {
        DB::beginTransaction();
        try {

          $cek = DB::table("keuangan")->where("keterangan", $req->keterangan)->first();

          if ($cek != null) {
            return response()->json(["status" => 7, "message" => "Keuangan dengan keterangan " . $cek->keterangan . " sudah terdaftar!"]);
          }

          $max = DB::table("keuangan")->max('id') + 1;

          $nominal = str_replace("", "Rp. ", $req->nominal);
          $nominal = str_replace("", ".", $nominal);

          $imgPath = null;
          $tgl = carbon::now('Asia/Jakarta');
          $folder = $tgl->year . $tgl->month . $tgl->timestamp;
          $dir = 'image/uploads/keuangan/' . $max;
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

          DB::table("keuangan")
              ->insert([
              "id" => $max,
              "keuangan_kategori_id" => $req->keuangan_kategori_id,
              "keterangan" => $req->keterangan,
              "nominal" => $nominal,
              "siswa_id" => $req->siswa_id,
              "bukti_pembayaran" => $imgPath,
              "created_at" => Carbon::now('Asia/Jakarta')
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

          $nominal = str_replace("", "Rp. ", $req->nominal);
          $nominal = str_replace("", ".", $nominal);

          $imgPath = null;
          $tgl = carbon::now('Asia/Jakarta');
          $folder = $tgl->year . $tgl->month . $tgl->timestamp;
          $dir = 'image/uploads/keuangan/' . $req->id;
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

          DB::table("keuangan")
            ->where('id', $req->id)
            ->update([
              "keuangan_kategori_id" => $req->keuangan_kategori_id,
              "keterangan" => $req->keterangan,
              "nominal" => $nominal,
              "siswa_id" => $req->siswa_id,
              "created_at" => Carbon::now('Asia/Jakarta')
          ]);

          if($imgPath != null) {
            DB::table("keuangan")
              ->where('id', $req->id)
              ->update([
                "bukti_pembayaran" => $imgPath
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

    public function hapus(Request $req) {
      DB::beginTransaction();
      try {

        DB::table("keuangan")
            ->where("id", $req->id)
            ->delete();

        DB::commit();
        return response()->json(["status" => 5]);
      } catch (\Exception $e) {
        DB::rollback();
        return response()->json(["status" => 6, "message" => $e->getMessage()]);
      }

    }

    public function edit(Request $req) {
      $data = DB::table("keuangan")
              ->where("id", $req->id)
              ->first();

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
