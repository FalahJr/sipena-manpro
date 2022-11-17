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

class WaliMuridController extends Controller
{
  public function index()
  {
    return view('wali_murid.index');
  }

  public function datatable()
  {
    $data = DB::table('wali_murid')
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
          '<a href="wali-murid/edit/' . $data->id . '" class="btn btn-info btn-lg">'.
          '<label class="fa fa-pencil-alt"></label></a>' .
          '<a href="/admin/wali-murid/hapus/'.$data->id.'" class="btn btn-danger btn-lg" title="hapus">' .
          '<label class="fa fa-trash"></label></a>' .
          '</div>';
      })
      ->rawColumns(['aksi', 'image'])
      ->addIndexColumn()
      ->make(true);
  }

  public function simpan(Request $req)
  {
    dd($req->all());
  }

  public function hapus($id)
  {
    $user_id = DB::table("wali_murid")
    ->where('id',$id)
    ->first();

    DB::table("wali_murid")
        ->where('id',$id)
        ->delete();

    DB::table("user")
        ->where('id',$user_id->user_id)
        ->delete();

      return back()->with(['success' => 'Data berhasil dihapus']);
  }

  public function edit($id)
  {
    $data = DB::table("wali_murid")->where("id", $id)->first();
    // dd($data);
    return view("wali_murid.edit", compact('data'));
    
  }

  public function update(Request $request)
  {
    $this->validate($request,[
      'email' => 'required|max:100',
      'nama_ayah' => 'required|max:100',
      'nama_ibu' => 'required|max:100',
      'no_telp' => 'required|max:14',
      'address' => 'required|max:255',
      'tanggal_lahir' => 'required|max:100',
    ]);
    $newData = request()->except(['_token','image']);
    $data = DB::table("wali_murid")->where('id',$request->id)->update($newData);

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
