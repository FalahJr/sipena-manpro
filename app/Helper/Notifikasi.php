<?php
namespace App\Helpers;
 
use Illuminate\Support\Facades\DB;
 
class Notifikasi {
    public static function push_notifikasi($user_id,$judul,$deskripsi,$tanggal) {
        $user = DB::table('notifikasi')
        ->insert([]);
        return ;
    }


    public static function get_notifikasi($user_id) {
        DB::table('notifikasi')->where('userid', $user_id)->update('is_seen', "Y");
        $data = DB::table('notifikasi')->where('userid', $user_id)->get();
        return response()->json(["status"=>1,"data"=>$data]);
    }

    public static function count_notifikasi($user_id) {
        $totalNotifikasi = DB::table('notifikasi')->where('userid', $user_id)->where('is_seen', "N")->count();
        return response()->json(["status"=>1,"data"=>$totalNotifikasi]);
    }
}
