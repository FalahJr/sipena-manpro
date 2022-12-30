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

class DinasPendidikanController extends Controller
{
  public function index()
  {
    return view('dinas-pendidikan.index');
  }

  public function datatable()
  {
    $data = DB::table('dinas_pendidikan')     
    ->get();


    // return $data;
    // $xyzab = collect($data);
    // return $xyzab;
    // return $xyzab->i_price;
    return Datatables::of($data)
    //   ->addColumn("image", function ($data) {
    //     return '<div> <img src="' . url('/') . '/' . $data->profile_picture . '" style="height: 100px; width:100px; border-radius: 0px;" class="img-responsive"> </img> </div>';
    //   })
      ->addColumn('aksi', function ($data) {
        return  '<div class="btn-group">' .
          '<a href="dinas-pendidikan/edit/' . $data->id . '" class="btn btn-info btn-lg">'.
          '<label class="fa fa-pencil-alt"></label></a>' .
          '<a href="dinas-pendidikan/hapus/'.$data->id.'" class="btn btn-danger btn-lg" title="hapus">' .
          '<label class="fa fa-trash"></label></a>' .
          '</div>';
      })->addColumn('foto_profil', function ($data) {
        $url= asset($data->foto_profil);
        return '<img src="' . $url . '" style="height: 80px; width:80px; border-radius: 0px;" class="img-responsive"> </img>';
      })
      ->rawColumns(['aksi', 'foto_profil'])
      ->addIndexColumn()
      ->make(true);
  }


  public function simpan(Request $req)
  {
      if (!$this->cekemail($req->username)) {
        return response()->json(["status" => 7, "message" => "Data username sudah digunakan, tidak dapat disimpan!"]);
      }
      DB::beginTransaction();
      try {
        $max = DB::table("user")->max('id') + 1;
        $maxWaliMurid = DB::table("dinas_pendidikan")->max('id') + 1;

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

      $tes=DB::table("user")
          ->insert([
            "id" => $max,
            "username" => $req->username,
            "password" => $req->password,
            "role_id" => 7,
            "is_active" => 'Y',
            "saldo" => 0,
            "created_at" => Carbon::now('Asia/Jakarta'),
          ]);
        
          DB::table("dinas_pendidikan")->insert([
            "id"=>$maxWaliMurid,
            "user_id" => $max,
            "nama_lengkap" => $req->nama_lengkap,
            "tanggal_lahir" => $req->tanggal_lahir,
            "jenis_kelamin" => $req->jk,
            "alamat" => $req->alamat,
            "phone" => $req->phone,
            "foto_profil" => $imgPath,
          ]);
        
          DB::commit();

        

        // }
        return response()->json(["status" => 1]);
      } catch (\Exception $e) {
        DB::rollback();
        return response()->json(["status" => 2,"message"=>$e]);
      }
  }

  public function hapus($id)
  {
    $dinas = DB::table("dinas_pendidikan")
    ->where('id',$id)
    ->first();
    
    DB::table("dinas_pendidikan")
        ->where('id',$id)
        ->delete();

    DB::table("user")
        ->where('id',$dinas->user_id)
        ->delete();

      return back()->with(['success' => 'Data berhasil dihapus']);
  }

  public static function cekemail($username, $id = null)
  {

    $cek = DB::table('user')->where("username", $username)->first();

    if ($cek != null) {
      if ($id != null) {
        if ($cek->id != $id) {
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
  public function edit($id)
  {
    $data = DB::table("dinas_pendidikan")->where("id", $id)->first();
    // dd($data);
    return view("dinas-pendidikan.edit", compact('data'));
    
  }

  public function update(Request $req)
  {
    $this->validate($req,[
      'nama_lengkap' => 'required|max:100',
      'phone' => 'required|max:14',
      'alamat' => 'required|max:255',
      'tanggal_lahir' => 'required|max:100',
    ]);
    $imgPath = null;
    $tgl = Carbon::now('Asia/Jakarta');
    $folder = $tgl->year . $tgl->month . $tgl->timestamp;
    $dir = 'image/uploads/User/' . $req->id;
    $childPath = $dir . '/';
    $path = $childPath;

    $file = $req->file('image');
    $name = null;
    $newData = request()->except(['_token','image']);
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
      $newData += ["foto_profil"=>$imgPath];
      DB::table("dinas_pendidikan")->where('id',$req->id)->update($newData); 
    }else{
      DB::table("dinas_pendidikan")->where('id',$req->id)->update($newData);
     }


    // dd($data);
    return back()->with(['success' => 'Data berhasil diupdate']);

    
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
