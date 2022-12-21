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

class GuruController extends Controller
{
  public function index()
  {
    return view('guru.index');
  }

  public function datatable()
  {
    $data = DB::table('guru')
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
          '<a href="guru/edit/' . $data->id . '" class="btn btn-info btn-lg">'.
          '<label class="fa fa-pencil-alt"></label></a>' .
          '<a href="/admin/guru/hapus/'.$data->id.'" class="btn btn-danger btn-lg" title="hapus">' .
          '<label class="fa fa-trash"></label></a>' .
          '</div>';
      })
      ->addColumn('foto_profil', function ($data) {
        $url= asset($data->profil_picture);
        return '<img src="' . $url . '" style="height: 80px; width:80px; border-radius: 0px;" class="img-responsive"> </img>';
      })
      ->rawColumns(['aksi', 'foto_profil'])
      ->addIndexColumn()
      ->make(true);
  }

  public function simpan(Request $req)
  {
    // dd(;

    if ($req->id == null) {
      if (!$this->cekemail($req->username)) {
        return response()->json(["status" => 7, "message" => "Data username sudah digunakan, tidak dapat disimpan!"]);
      }
      DB::beginTransaction();
//       DB::transaction(function()
// {
    // DB::table('users')->update(['votes' => 1]);
 
    // DB::table('posts')->delete();
      try {

        $max = DB::table("user")->max('id') + 1;
        $maxGuru = DB::table("guru")->max('id') + 1;

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
            "role_id" => 4,
            "is_active" => 'Y',
            "saldo" => 0,
            "created_at" => Carbon::now('Asia/Jakarta'),
          ]);
          if($tes){
          DB::table("guru")->insert([
            "id"=>$maxGuru,
            "user_id" => $max,
            "nama_lengkap" => $req->nama_lengkap,
            "tanggal_lahir" => $req->tgl_lahir,
            "phone" => $req->no_hp,
            "alamat" => $req->alamat,
            "jk" => $req->jk,
            "is_walikelas" => 'N',
            "is_ekstrakulikuler" => 'N',
            "is_mapel" => 'N',
            "created_at" => Carbon::now('Asia/Jakarta'),
          ]);
        }
          DB::commit();

        

        // }
        return response()->json(["status" => 1]);
      } catch (\Exception $e) {
        DB::rollback();
        return response()->json(["status" => 2]);
      }
// });


    }
     else 
     {
      if (!$this->cekemail($req->username, $req->id)) {
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
          $tes= DB::table("user")
            ->where('user', $req->id)
            ->update([
              // "id" => $max,
            "username" => $req->username,
            "password" => $req->password,
            "role_id" => 4,
            "is_active" => 'Y',
            "saldo" => 0,
            "created_at" => Carbon::now('Asia/Jakarta'),
            ]);
            if($tes){
              DB::table("guru")->insert([
                // "id"=>$maxGuru,
                // "user_id" => $max,
                "nama_lengkap" => $req->nama_lengkap,
                "tanggal_lahir" => $req->tgl_lahir,
                "phone" => $req->no_hp,
                "alamat" => $req->alamat,
                "jk" => $req->jk,
                "is_walikelas" => 'N',
                "is_ekstrakulikuler" => 'N',
                "is_mapel" => 'N',
                "created_at" => Carbon::now('Asia/Jakarta'),
              ]);
            }
        } else {
          $tes=  DB::table("user")
            ->where('user', $req->id)
            ->update([
              "id" => $max,
            "username" => $req->username,
            "password" => $req->password,
            "role_id" => 4,
            "is_active" => 'Y',
            "saldo" => 0,
            "created_at" => Carbon::now('Asia/Jakarta'),
            ]);
            if($tes){
              DB::table("guru")->insert([
                "id"=>$maxGuru,
                "user_id" => $max,
                "nama_lengkap" => $req->nama_lengkap,
                "tanggal_lahir" => $req->tgl_lahir,
                "phone" => $req->no_hp,
                "alamat" => $req->alamat,
                "jk" => $req->jk,
                "is_walikelas" => 'N',
                "is_ekstrakulikuler" => 'N',
                "is_mapel" => 'N',
                "created_at" => Carbon::now('Asia/Jakarta'),
              ]);
            }
        }

        // $tes = DB::commit();
        
          DB::commit();
        
        return response()->json(["status" => 3]);
      } catch (\Exception $e) {
        DB::rollback();
        return response()->json(["status" => 4]);
      }
    }
  }

  public function hapus($id)
  {
    $user_id = DB::table("guru")
    ->where('id',$id)
    ->first();

    DB::table("guru")
        ->where('id',$id)
        ->delete();

    DB::table("user")
        ->where('id',$user_id->user_id)
        ->delete();

      return back()->with(['success' => 'Data berhasil dihapus']);
  }

  public function edit($id)
  {
    $data = DB::table("guru")->where("id", $id)->first();
    // dd($data);
    return view("guru.edit", compact('data'));
    
  }

  public function update(Request $request)
  {
    $this->validate($request,[
      'nama_lengkap' => 'required|max:100',
      'phone' => 'required|max:14',
      'alamat' => 'required|max:100',
      'tanggal_lahir' => 'required|max:100',
      'jk' => 'required|max:2',
    ]);

    $imgPath = null;
    $tgl = Carbon::now('Asia/Jakarta');
    $folder = $tgl->year . $tgl->month . $tgl->timestamp;
    $dir = 'image/uploads/Guru/' . $request->id;
    $childPath = $dir . '/';
    $path = $childPath;

    $file = $request->file('image');
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
      $newData += ["profil_picture"=>$imgPath];
      // DB::table("siswa")->where('id',$req->id)->update($newData); 
    $data = DB::table("guru")->where('id',$request->id)->update($newData);

    }else{
      // DB::table("siswa")->where('id',$req->id)->update($newData);
    $data = DB::table("guru")->where('id',$request->id)->update($newData);

     }


    // dd($data);
    return back()->with(['success' => 'Data berhasil diupdate']);

    
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
