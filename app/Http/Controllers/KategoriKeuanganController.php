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

class KategoriKeuanganController extends Controller
{
    public static function getKategoriKeuangan()
    {
        $data = DB::table("keuangan_kategori")->get()->toArray();

        return $data;
    }

    public static function getKategoriKeuanganJson() {
      $data = KategoriKeuanganController::getKategoriKeuangan();

      return response()->json($data);
    }

    public function index() {
      $data2 = DB::table("keuangan_kategori")
                ->get();

      return view('kategori-keuangan.index', compact('data2'));
    }

    public function datatable() {
      $data = KategoriKeuanganController::getKategoriKeuangan();

        return Datatables::of($data)
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

          $cek = DB::table("keuangan_kategori")->where("nama", $req->nama_kategori)->first();

          if ($cek != null) {
            return response()->json(["status" => 7, "message" => "Kategori Keuangan dengan nama " . $cek->nama . " sudah terdaftar!"]);
          }

          $max = DB::table("keuangan_kategori")->max('id') + 1;

          

          DB::table("keuangan_kategori")
              ->insert([
              "id" => $max,
              "nama" => $req->nama_kategori,
              
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
              DB::table("keuangan_kategori")
                  ->where('id', $req->id)
                  ->update([
                    "nama" => $req->nama_kategori,
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

        DB::table("keuangan_kategori")
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
      $data = DB::table("keuangan_kategori")
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
