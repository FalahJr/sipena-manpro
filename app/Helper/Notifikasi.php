<?php
namespace App\Helpers;
 
use Illuminate\Support\Facades\DB;
 
class Notifikasi {
    public static function push_notifikasi($user_id,$judul,$deskripsi) {
        DB::table('notifikasi')
        ->insert([
            "user_id"=>$user_id,
            "judul"=>$judul,
            "deskripsi"=>$deskripsi,
            "created_at"=>date("Y-m-d"),
            "is_seen"=>"N",
        ]);
    }


    public static function get_notifikasi($user_id) {
        DB::table('notifikasi')->where('user_id', $user_id)->update('is_seen', "Y");
        $data = DB::table('notifikasi')->where('user_id', $user_id)->get();
        return response()->json(["status"=>1,"data"=>$data]);
    }

    public static function count_notifikasi($user_id) {
        $totalNotifikasi = DB::table('notifikasi')->where('user_id', $user_id)->where('is_seen', "N")->count();
        return response()->json(["status"=>1,"data"=>$totalNotifikasi]);
    }
}
