<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Account;

use App\Authentication;

use Auth;

use Carbon\Carbon;

use Session;

use DB;

use File;

use Yajra\Datatables\Datatables;

use Response;

class UserController extends Controller
{
  public function index()
  {
    return view('user.index');
  }

  public function datatable()
  {
    $data = DB::table('account')
      ->get();


    // return $data;
    // $xyzab = collect($data);
    // return $xyzab;
    // return $xyzab->i_price;
    return Datatables::of($data)
      ->addColumn("image", function ($data) {
        return '<div> <img src="' . url('/') . '/' . $data->profile_picture . '" style="height: 100px; width:100px; border-radius: 0px;" class="img-responsive"> </img> </div>';
      })
      ->addColumn('aksi', function ($data) {
        return  '<div class="btn-group">' .
          '<button type="button" onclick="edit(' . $data->id_account . ')" class="btn btn-info btn-lg" title="edit">' .
          '<label class="fa fa-pencil-alt"></label></button>' .
          '<button type="button" onclick="hapus(' . $data->id_account . ')" class="btn btn-danger btn-lg" title="hapus">' .
          '<label class="fa fa-trash"></label></button>' .
          '</div>';
      })
      ->rawColumns(['aksi', 'image'])
      ->addIndexColumn()
      ->make(true);
  }

  public function simpan(Request $req)
  {
    // dd(;

    if ($req->id == null) {
      if (!$this->cekemail($req->email)) {
        return response()->json(["status" => 7, "message" => "Data email sudah digunakan, tidak dapat disimpan!"]);
      }
      DB::beginTransaction();
      try {

        $max = DB::table("account")->max('id_account') + 1;

        $imgPath = null;
        $tgl = Carbon::now('Asia/Jakarta');
        $folder = $tgl->year . $tgl->month . $tgl->timestamp;
        $dir = 'image/uploads/User/' . $max;
        $childPath = $dir . '/';
        $path = $childPath;

        $file = $req->file('image');
        $name = null;
        if ($file != null) {
          $this->deleteDir($dir);
          $name = $folder . '.' . $file->getClientOriginalExtension();
          if (!File::exists($path)) {
            if (File::makeDirectory($path, 0777, true)) {
              if ($_FILES['image']['type'] == 'image/webp' || $_FILES['image']['type'] == 'image/jpeg') {
              } else if ($_FILES['image']['type'] == 'webp' || $_FILES['image']['type'] == 'jpeg') {
              } else {
                compressImage($_FILES['image']['type'], $_FILES['image']['tmp_name'], $_FILES['image']['tmp_name'], 75);
              }
              $file->move($path, $name);
              $imgPath = $childPath . $name;
            } else
              $imgPath = null;
          } else {
            return 'already exist';
          }
        }

        DB::table("account")
          ->insert([
            "id_account" => $max,
            "fullname" => $req->fullname,
            "email" => $req->email,
            "password" => $req->password,
            "confirm_password" => $req->password,
            "role" => $req->role,
            "phone" => $req->phone,
            "address" => $req->address,
            "gender" => $req->gender,
            "profile_picture" => $imgPath,
            "nomor_rekening" => $req->nomor_rekening,
            "bank" => $req->bank,
            "created_at" => Carbon::now('Asia/Jakarta'),
          ]);

        DB::commit();
        return response()->json(["status" => 1]);
      } catch (\Exception $e) {
        DB::rollback();
        return response()->json(["status" => 2]);
      }
    } else {
      if (!$this->cekemail($req->email, $req->id)) {
        return response()->json(["status" => 7, "message" => "Data email sudah digunakan, tidak dapat disimpan!"]);
      }
      DB::beginTransaction();
      try {

        $imgPath = null;
        $tgl = Carbon::now('Asia/Jakarta');
        $folder = $tgl->year . $tgl->month . $tgl->timestamp;
        $dir = 'image/uploads/User/' . $req->id;
        $childPath = $dir . '/';
        $path = $childPath;

        $file = $req->file('image');
        $name = null;
        if ($file != null) {
          $this->deleteDir($dir);
          $name = $folder . '.' . $file->getClientOriginalExtension();
          if (!File::exists($path)) {
            if (File::makeDirectory($path, 0777, true)) {
              if ($_FILES['image']['type'] == 'image/webp' || $_FILES['image']['type'] == 'image/jpeg') {
              } else if ($_FILES['image']['type'] == 'webp' || $_FILES['image']['type'] == 'jpeg') {
              } else {
                compressImage($_FILES['image']['type'], $_FILES['image']['tmp_name'], $_FILES['image']['tmp_name'], 75);
              }
              $file->move($path, $name);
              $imgPath = $childPath . $name;
            } else
              $imgPath = null;
          } else {
            return 'already exist';
          }
        }

        if ($imgPath == null) {
          DB::table("account")
            ->where('id_account', $req->id)
            ->update([
              "fullname" => $req->fullname,
              "email" => $req->email,
              "password" => $req->password,
              "confirm_password" => $req->password,
              "role" => $req->role,
              "phone" => $req->phone,
              "address" => $req->address,
              "gender" => $req->gender,
              "nomor_rekening" => $req->nomor_rekening,
              "bank" => $req->bank,
              "updated_at" => Carbon::now('Asia/Jakarta'),
            ]);
        } else {
          DB::table("account")
            ->where('id_account', $req->id)
            ->update([
              "fullname" => $req->fullname,
              "email" => $req->email,
              "password" => $req->password,
              "confirm_password" => $req->password,
              "role" => $req->role,
              "phone" => $req->phone,
              "address" => $req->address,
              "gender" => $req->gender,
              "profile_picture" => $imgPath,
              "nomor_rekening" => $req->nomor_rekening,
              "bank" => $req->bank,
              "updated_at" => Carbon::now('Asia/Jakarta'),
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

  public function hapus(Request $req)
  {
    DB::beginTransaction();
    try {

      DB::table("account")
        ->where("id_account", $req->id)
        ->delete();

      $dir = 'image/uploads/User/' . $req->id;

      $this->deleteDir($dir);

      DB::commit();
      return response()->json(["status" => 3]);
    } catch (\Exception $e) {
      DB::rollback();
      return response()->json(["status" => 4]);
    }
  }

  public function edit(Request $req)
  {
    $data = DB::table("account")
      ->where("id_account", $req->id)
      ->first();

    return response()->json($data);
  }

  public static function cekemail($email, $id = null)
  {

    $cek = DB::table('account')->where("email", $email)->first();

    if ($cek != null) {
      if ($id != null) {
        if ($cek->id_account != $id) {
          return false;
        } else {
          return true;
        }
      } else {
        return false;
      }
    } else {
      return true;
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
