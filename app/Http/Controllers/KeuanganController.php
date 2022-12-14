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
        $data = DB::table("keuangan")->get()->toArray();

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

          

          DB::table("keuangan")
              ->insert([
              "id" => $max,
              "keuangan_kategori_id" => $req->keuangan_kategori_id,
              "keterangan" => $req->keterangan,
              "nominal" => $req->nominal,
              "siswa_id" => $req->siswa_id,
              "bukti_pembayaran" => null,
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

         

            if ($req->nama_kategori != null) {
              DB::table("keuangan")
                  ->where('id', $req->id)
                  ->update([
                    "keuangan_kategori_id" => $req->keuangan_kategori_id,
              "keterangan" => $req->keterangan,
              "nominal" => $req->nominal,
              "siswa_id" => $req->siswa_id,
                "created_at" => Carbon::now('Asia/Jakarta')

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
