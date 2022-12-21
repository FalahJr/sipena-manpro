<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class NotifikasiController extends Controller{
    static function push_notifikasi($user_id,$judul,$deskripsi) {
        return DB::table('notifikasi')
        ->insert([
            "user_id"=>$user_id,
            "judul"=>$judul,
            "deskripsi"=>$deskripsi,
            "created_at"=>date("Y-m-d"),
            "is_seen"=>"N",
        ]);
    }

    static function get_notifikasi(Request $req) {
        DB::table('notifikasi')->where('user_id', $req->user_id)->update(['is_seen'=>'Y']);
        $data = DB::table('notifikasi')->where('user_id', $req->user_id)->get();
        return response()->json(["status"=>1,"data"=>$data]);
    }

    static function getNotif($userid) {
        $data = DB::table('notifikasi')->where('user_id', $userid)->get();
        return response()->json(["status"=>1,"data"=>$data]);
    }

    static function count_notifikasi(Request $req) {
        $totalNotifikasi = DB::table('notifikasi')->where('user_id', $req->user_id)->where('is_seen', "N")->count();
        return response()->json(["status"=>1,"data"=>$totalNotifikasi]);
    }
}