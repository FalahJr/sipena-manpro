<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use DB;
use Auth;

class Account extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable,
        CanResetPassword;

        // protected $table = 'account';
        //     protected $primaryKey = 'id_account';
        //     public $incrementing = false;
        //     public $remember_token = false;
        //     //public $timestamps = false;
        //     protected $fillable = ['id_account','fullname', 'email','password','confirm_password','role','phone','gender','address','profile_picture','profile_toko', 'nama_toko', 'islogin', 'istoko', 'created_at', 'update_at'];

        protected $table = 'user';
            protected $primaryKey = 'id';
            public $incrementing = false;
            public $remember_token = false;
            //public $timestamps = false;
            protected $fillable = ['id','username','password','role_id','is_login','is_active','saldo', 'created_at', 'updated_at'];
}
