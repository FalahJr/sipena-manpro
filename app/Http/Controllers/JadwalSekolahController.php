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

class JadwalSekolahController extends Controller
{
  public function index()
  {
    return view("jadwal-sekolah.index");
  }

  public function datatable()
  {
    $data = DB::table('jadwal_sekolah')
      ->get();
      

    // return $data;
    // $xyzab = collect($data);
    // return $xyzab;
    // return $xyzab->i_price;
    return Datatables::of($data)
      ->addColumn('aksi', function ($data) {
        return '<div class="btn-group">' .
          '<a href="jadwal-sekolah/edit/' . $data->id . '" class="btn btn-info btn-lg">' .
          '<label class="fa fa-pencil-alt"></label></a>' .
          '<a href="jadwal-sekolah/hapus/' . $data->id . '" class="btn btn-danger btn-lg" title="hapus">' .
          '<label class="fa fa-trash"></label></a>' .
          '</div>';
      })
      ->rawColumns(['aksi'])
      ->addIndexColumn()
      ->make(true);
  }

  public function simpan(Request $req)
  {
    // dd(;
    $max = DB::table("jadwal_sekolah")->max('id') + 1;

      if (!$this->cekemail($req->jadwal_hari)) {
        return response()->json(["status" => 7, "message" => "Jadwal Hari ".$req->jadwal_hari.", Sudah ada!"]);
      }
      DB::beginTransaction();

      try {
        DB::table("jadwal_sekolah")
          ->insert([
            "id" => $max,
            "kegiatan" => $req->kegiatan,
            "jadwal_hari" => $req->jadwal_hari,
            "jam_mulai" => $req->jam_mulai,
            "jam_selesai" => $req->jam_selesai,
            "created_at" => Carbon::now('Asia/Jakarta'),
          ]);
        DB::commit();
        return response()->json(["status" => 1]);
      } catch (\Exception $e) {
        DB::rollback();
        return response()->json(["status" => 7, "message" => $e]);
      }
    
  }

  public function hapus($id)
  {
    DB::table("jadwal_sekolah")
      ->where('id', $id)
      ->delete();

    return back()->with(['success' => 'Data berhasil dihapus']);
  }

  public function edit($id)
  {
    $detail = DB::table("jadwal_sekolah")->where("id", $id)->first();
    // dd($data);
    return view("jadwal-sekolah.edit", compact('detail'));

  }

  public function getData(){
    try{
      $data = DB::table('jadwal_sekolah')->get();
      return response()->json(["status" => 1, "data"=>$data]);
    } catch (\Exception $e) {
      DB::rollback();
      return response()->json(["status" => 2, "message" => $e->getMessage()]);
    }
  }

  public function update(Request $request)
  {
    $this->validate($request, [
      'kegiatan' => 'required|max:100',
      'jadwal_hari' => 'required|max:100',
      'jam_mulai' => 'required|max:5',
      'jam_selesai' => 'required|max:5',
    ]);

    $newData = request()->except(['_token']);
    DB::table("jadwal_sekolah")->where('id', $request->id)->update($newData);
    return back()->with(['success' => 'Data berhasil diupdate']);

  }

  public static function cekemail($jadwal, $id = null)
  {

    $cek = DB::table('jadwal_sekolah')->where("jadwal_hari", $jadwal)->first();

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

}