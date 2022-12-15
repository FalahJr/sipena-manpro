<?php
namespace App\Http\Controllers;

class Notifikasi{
    static function push_notifikasi($user_id,$judul,$deskripsi) {
        DB::table('notifikasi')
        ->insert([
            "user_id"=>$user_id,
            "judul"=>$judul,
            "deskripsi"=>$deskripsi,
            "created_at"=>date("Y-m-d"),
            "is_seen"=>"N",
        ]);
    }

    static function get_notifikasi($user_id) {
        DB::table('notifikasi')->where('userid', $user_id)->update('is_seen', "Y");
        $data = DB::table('notifikasi')->where('userid', $user_id)->get();
        return response()->json(["status"=>1,"data"=>$data]);
    }

    static function count_notifikasi($user_id) {
        $totalNotifikasi = DB::table('notifikasi')->where('userid', $user_id)->where('is_seen', "N")->count();
        return response()->json(["status"=>1,"data"=>$totalNotifikasi]);
    }
}