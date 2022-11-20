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

class MutasiSiswaController extends Controller
{
    public function index() {
      $data2 = DB::table("siswa")
                ->get();

      return view('mutasisiswa.index', compact('data2'));
    }

    public function datatable() {
      $data = DB::table("siswa_mutasi")
        ->join("siswa", "siswa.id", '=', 'siswa_mutasi.siswa_id')
        ->select("siswa.nama_lengkap", "siswa_mutasi.id")
        ->get()->toArray();

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

          $cek = DB::table("siswa_mutasi")
                  ->join("siswa", "siswa.id", '=', 'siswa_mutasi.siswa_id')
                  ->where("siswa_id", $req->siswa_id)
                  ->first();

          if ($cek != null) {
            return response()->json(["status" => 7, "message" => "Mutasi siswa atas nama " . $cek->nama_lengkap . " sudah terdaftar!"]);
          }

          $max = DB::table("siswa_mutasi")->max('id') + 1;

          $imgPath = null;
          $tgl = carbon::now('Asia/Jakarta');
          $folder = $tgl->year . $tgl->month . $tgl->timestamp;
          $dir = 'image/uploads/siswa_mutasi/' . $max . '/surat_keterangan_pindah_sekolah_asal';
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

          $imgPath1 = null;
          $tgl = carbon::now('Asia/Jakarta');
          $folder = $tgl->year . $tgl->month . $tgl->timestamp;
          $dir = 'image/uploads/siswa_mutasi/' . $max . '/tanda_bukti_mutasi_dispen_provinsi';
          $childPath = $dir . '/';
          $path = $childPath;

          $file = $req->file('image1');
          $name = null;
          if ($file != null) {
              $this->deleteDir($dir);
              $name = $folder . '.' . $file->getClientOriginalExtension();
              if (!File::exists($path)) {
                  if (File::makeDirectory($path, 0777, true)) {
                    // compressImage($_FILES['image']['type'],$_FILES['image']['tmp_name'],$_FILES['image']['tmp_name'],50);
                      $file->move($path, $name);
                      $imgPath1 = $childPath . $name;
                  } else
                      $imgPath1 = null;
              } else {
                  return 'already exist';
              }
          }

          $imgPath2 = null;
          $tgl = carbon::now('Asia/Jakarta');
          $folder = $tgl->year . $tgl->month . $tgl->timestamp;
          $dir = 'image/uploads/siswa_mutasi/' . $max . '/surat_rekom';
          $childPath = $dir . '/';
          $path = $childPath;

          $file = $req->file('image2');
          $name = null;
          if ($file != null) {
              $this->deleteDir($dir);
              $name = $folder . '.' . $file->getClientOriginalExtension();
              if (!File::exists($path)) {
                  if (File::makeDirectory($path, 0777, true)) {
                    // compressImage($_FILES['image']['type'],$_FILES['image']['tmp_name'],$_FILES['image']['tmp_name'],50);
                      $file->move($path, $name);
                      $imgPath2 = $childPath . $name;
                  } else
                      $imgPath2 = null;
              } else {
                  return 'already exist';
              }
          }

          $imgPath3 = null;
          $tgl = carbon::now('Asia/Jakarta');
          $folder = $tgl->year . $tgl->month . $tgl->timestamp;
          $dir = 'image/uploads/siswa_mutasi/' . $max . '/raport_asal';
          $childPath = $dir . '/';
          $path = $childPath;

          $file = $req->file('image3');
          $name = null;
          if ($file != null) {
              $this->deleteDir($dir);
              $name = $folder . '.' . $file->getClientOriginalExtension();
              if (!File::exists($path)) {
                  if (File::makeDirectory($path, 0777, true)) {
                    // compressImage($_FILES['image']['type'],$_FILES['image']['tmp_name'],$_FILES['image']['tmp_name'],50);
                      $file->move($path, $name);
                      $imgPath3 = $childPath . $name;
                  } else
                      $imgPath3 = null;
              } else {
                  return 'already exist';
              }
          }

          $imgPath4 = null;
          $tgl = carbon::now('Asia/Jakarta');
          $folder = $tgl->year . $tgl->month . $tgl->timestamp;
          $dir = 'image/uploads/siswa_mutasi/' . $max . '/fotocoy_raport';
          $childPath = $dir . '/';
          $path = $childPath;

          $file = $req->file('image4');
          $name = null;
          if ($file != null) {
              $this->deleteDir($dir);
              $name = $folder . '.' . $file->getClientOriginalExtension();
              if (!File::exists($path)) {
                  if (File::makeDirectory($path, 0777, true)) {
                    // compressImage($_FILES['image']['type'],$_FILES['image']['tmp_name'],$_FILES['image']['tmp_name'],50);
                      $file->move($path, $name);
                      $imgPath4 = $childPath . $name;
                  } else
                      $imgPath4 = null;
              } else {
                  return 'already exist';
              }
          }

          $imgPath5 = null;
          $tgl = carbon::now('Asia/Jakarta');
          $folder = $tgl->year . $tgl->month . $tgl->timestamp;
          $dir = 'image/uploads/siswa_mutasi/' . $max . '/fotocopy_sertifikat';
          $childPath = $dir . '/';
          $path = $childPath;

          $file = $req->file('image5');
          $name = null;
          if ($file != null) {
              $this->deleteDir($dir);
              $name = $folder . '.' . $file->getClientOriginalExtension();
              if (!File::exists($path)) {
                  if (File::makeDirectory($path, 0777, true)) {
                    // compressImage($_FILES['image']['type'],$_FILES['image']['tmp_name'],$_FILES['image']['tmp_name'],50);
                      $file->move($path, $name);
                      $imgPath5 = $childPath . $name;
                  } else
                      $imgPath5 = null;
              } else {
                  return 'already exist';
              }
          }

          $imgPath6 = null;
          $tgl = carbon::now('Asia/Jakarta');
          $folder = $tgl->year . $tgl->month . $tgl->timestamp;
          $dir = 'image/uploads/siswa_mutasi/' . $max . '/surat_rekomendasi_penerimaan';
          $childPath = $dir . '/';
          $path = $childPath;

          $file = $req->file('image6');
          $name = null;
          if ($file != null) {
              $this->deleteDir($dir);
              $name = $folder . '.' . $file->getClientOriginalExtension();
              if (!File::exists($path)) {
                  if (File::makeDirectory($path, 0777, true)) {
                    // compressImage($_FILES['image']['type'],$_FILES['image']['tmp_name'],$_FILES['image']['tmp_name'],50);
                      $file->move($path, $name);
                      $imgPath6 = $childPath . $name;
                  } else
                      $imgPath6 = null;
              } else {
                  return 'already exist';
              }
          }

          DB::table("siswa_mutasi")
              ->insert([
              "id" => $max,
              "siswa_id" => $req->siswa_id,
              "surat_keterangan_pindah_sekolah_asal" => $imgPath,
              "tanda_bukti_mutasi_dispen_provinsi" => $imgPath1,
              "surat_rekom_penyaluran_dari_deriktorat_jendral_dikdasmen" => $imgPath2,
              "raport_asal" => $imgPath3,
              "fotocoy_raport" => $imgPath4,
              "fotocopy_sertifikat" => $imgPath5,
              "surat_rekomendasi_penerimaan" => $imgPath6,
            ]);

          DB::commit();
          return response()->json(["status" => 1]);
        } catch (\Exception $e) {
          DB::rollback();
          return response()->json(["status" => 2]);
        }
      } else {
        DB::beginTransaction();
        try {

          $imgPath = null;
          $tgl = carbon::now('Asia/Jakarta');
          $folder = $tgl->year . $tgl->month . $tgl->timestamp;
          $dir = 'image/uploads/siswa_mutasi/' . $req->id . '/surat_keterangan_pindah_sekolah_asal';
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

          $imgPath1 = null;
          $tgl = carbon::now('Asia/Jakarta');
          $folder = $tgl->year . $tgl->month . $tgl->timestamp;
          $dir = 'image/uploads/siswa_mutasi/' . $req->id . '/tanda_bukti_mutasi_dispen_provinsi';
          $childPath = $dir . '/';
          $path = $childPath;

          $file = $req->file('image1');
          $name = null;
          if ($file != null) {
              $this->deleteDir($dir);
              $name = $folder . '.' . $file->getClientOriginalExtension();
              if (!File::exists($path)) {
                  if (File::makeDirectory($path, 0777, true)) {
                    // compressImage($_FILES['image']['type'],$_FILES['image']['tmp_name'],$_FILES['image']['tmp_name'],50);
                      $file->move($path, $name);
                      $imgPath1 = $childPath . $name;
                  } else
                      $imgPath1 = null;
              } else {
                  return 'already exist';
              }
          }

          $imgPath2 = null;
          $tgl = carbon::now('Asia/Jakarta');
          $folder = $tgl->year . $tgl->month . $tgl->timestamp;
          $dir = 'image/uploads/siswa_mutasi/' . $req->id . '/surat_rekom';
          $childPath = $dir . '/';
          $path = $childPath;

          $file = $req->file('image2');
          $name = null;
          if ($file != null) {
              $this->deleteDir($dir);
              $name = $folder . '.' . $file->getClientOriginalExtension();
              if (!File::exists($path)) {
                  if (File::makeDirectory($path, 0777, true)) {
                    // compressImage($_FILES['image']['type'],$_FILES['image']['tmp_name'],$_FILES['image']['tmp_name'],50);
                      $file->move($path, $name);
                      $imgPath2 = $childPath . $name;
                  } else
                      $imgPath2 = null;
              } else {
                  return 'already exist';
              }
          }

          $imgPath3 = null;
          $tgl = carbon::now('Asia/Jakarta');
          $folder = $tgl->year . $tgl->month . $tgl->timestamp;
          $dir = 'image/uploads/siswa_mutasi/' . $req->id . '/raport_asal';
          $childPath = $dir . '/';
          $path = $childPath;

          $file = $req->file('image3');
          $name = null;
          if ($file != null) {
              $this->deleteDir($dir);
              $name = $folder . '.' . $file->getClientOriginalExtension();
              if (!File::exists($path)) {
                  if (File::makeDirectory($path, 0777, true)) {
                    // compressImage($_FILES['image']['type'],$_FILES['image']['tmp_name'],$_FILES['image']['tmp_name'],50);
                      $file->move($path, $name);
                      $imgPath3 = $childPath . $name;
                  } else
                      $imgPath3 = null;
              } else {
                  return 'already exist';
              }
          }

          $imgPath4 = null;
          $tgl = carbon::now('Asia/Jakarta');
          $folder = $tgl->year . $tgl->month . $tgl->timestamp;
          $dir = 'image/uploads/siswa_mutasi/' . $req->id . '/fotocoy_raport';
          $childPath = $dir . '/';
          $path = $childPath;

          $file = $req->file('image4');
          $name = null;
          if ($file != null) {
              $this->deleteDir($dir);
              $name = $folder . '.' . $file->getClientOriginalExtension();
              if (!File::exists($path)) {
                  if (File::makeDirectory($path, 0777, true)) {
                    // compressImage($_FILES['image']['type'],$_FILES['image']['tmp_name'],$_FILES['image']['tmp_name'],50);
                      $file->move($path, $name);
                      $imgPath4 = $childPath . $name;
                  } else
                      $imgPath4 = null;
              } else {
                  return 'already exist';
              }
          }

          $imgPath5 = null;
          $tgl = carbon::now('Asia/Jakarta');
          $folder = $tgl->year . $tgl->month . $tgl->timestamp;
          $dir = 'image/uploads/siswa_mutasi/' . $req->id . '/fotocopy_sertifikat';
          $childPath = $dir . '/';
          $path = $childPath;

          $file = $req->file('image5');
          $name = null;
          if ($file != null) {
              $this->deleteDir($dir);
              $name = $folder . '.' . $file->getClientOriginalExtension();
              if (!File::exists($path)) {
                  if (File::makeDirectory($path, 0777, true)) {
                    // compressImage($_FILES['image']['type'],$_FILES['image']['tmp_name'],$_FILES['image']['tmp_name'],50);
                      $file->move($path, $name);
                      $imgPath5 = $childPath . $name;
                  } else
                      $imgPath5 = null;
              } else {
                  return 'already exist';
              }
          }

          $imgPath6 = null;
          $tgl = carbon::now('Asia/Jakarta');
          $folder = $tgl->year . $tgl->month . $tgl->timestamp;
          $dir = 'image/uploads/siswa_mutasi/' . $req->id . '/surat_rekomendasi_penerimaan';
          $childPath = $dir . '/';
          $path = $childPath;

          $file = $req->file('image6');
          $name = null;
          if ($file != null) {
              $this->deleteDir($dir);
              $name = $folder . '.' . $file->getClientOriginalExtension();
              if (!File::exists($path)) {
                  if (File::makeDirectory($path, 0777, true)) {
                    // compressImage($_FILES['image']['type'],$_FILES['image']['tmp_name'],$_FILES['image']['tmp_name'],50);
                      $file->move($path, $name);
                      $imgPath6 = $childPath . $name;
                  } else
                      $imgPath6 = null;
              } else {
                  return 'already exist';
              }
          }

            if ($imgPath != null) {
              DB::table("siswa_mutasi")
                  ->where('id', $req->id)
                  ->update([
                    "surat_keterangan_pindah_sekolah_asal" => $imgPath,
                ]);
            }

            if ($imgPath1 != null) {
              DB::table("siswa_mutasi")
                  ->where('id', $req->id)
                  ->update([
                    "tanda_bukti_mutasi_dispen_provinsi" => $imgPath1,
                ]);
            }

            if ($imgPath2 != null) {
              DB::table("siswa_mutasi")
                  ->where('id', $req->id)
                  ->update([
                    "surat_rekom_penyaluran_dari_deriktorat_jendral_dikdasmen" => $imgPath2,
                ]);
            }

            if ($imgPath3 != null) {
              DB::table("siswa_mutasi")
                  ->where('id', $req->id)
                  ->update([
                    "raport_asal" => $imgPath3,
                ]);
            }

            if ($imgPath4 != null) {
              DB::table("siswa_mutasi")
                  ->where('id', $req->id)
                  ->update([
                    "fotocoy_raport" => $imgPath4,
                ]);
            }

            if ($imgPath5 != null) {
              DB::table("siswa_mutasi")
                  ->where('id', $req->id)
                  ->update([
                    "fotocopy_sertifikat" => $imgPath5,
                ]);
            }

            if ($imgPath6 != null) {
              DB::table("siswa_mutasi")
                  ->where('id', $req->id)
                  ->update([
                    "surat_rekomendasi_penerimaan" => $imgPath6,
                ]);
            }

            if ($req->siswa_id != null) {
              DB::table("siswa_mutasi")
                  ->where('id', $req->id)
                  ->update([
                    "siswa_id" => $req->siswa_id,
                ]);
            }

          DB::commit();
          return response()->json(["status" => 3]);
        } catch (\Exception $e) {
          DB::rollback();
          return response()->json(["status" => 4]);
        }
      }

    }

    public function hapus(Request $req) {
      DB::beginTransaction();
      try {

        DB::table("siswa_mutasi")
            ->where("id", $req->id)
            ->delete();

        $dir = 'image/uploads/siswa_mutasi/' . $req->id;

        $this->deleteDir($dir);

        DB::commit();
        return response()->json(["status" => 5]);
      } catch (\Exception $e) {
        DB::rollback();
        return response()->json(["status" => 6]);
      }

    }

    public function edit(Request $req) {
      $data = DB::table("siswa_mutasi")
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
