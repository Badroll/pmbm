<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Support\Facades\Hash;

class User extends Model implements AuthenticatableContract
{
    use Authenticatable;

    protected $table = "_user";
    protected $primaryKey = "U_ID";
    public $timestamps = false;
    protected $guarded = [];
    protected $hidden = [
        "U_PASSWORD",
    ];


    // RELATION ------------------------------------------------------------------------------------------------------

    //
    public function inbox(){
        return $this->hasMany(Inbox::class, "U_ID", "U_ID")->orderBy("INBOX_ID", "DESC");
    }

    //
    public function siswa(){
        return $this->hasOne(Siswa::class, "U_ID", "U_ID");
    }

    //
    public function refRole(){
        return $this->hasOne(_reference::class, "R_ID", "U_ROLE");
    }

    //
    public function refAccountStatus(){
        return $this->hasOne(_reference::class, "R_ID", "U_ACCOUNT_STATUS");
    }


    // CUSTOM ---------------------------------------------------------------------------------------------------------

    public static function generateDummy($count = 1, $role = "ROLE_SISWA"){
        for ($i = 1; $i <= $count; $i++) {
            $user = [
                "U_EMAIL" => "user" . $i . "@example.com",
                "U_USERNAME" => "user_" . $role . "_".  $i,
                "U_PASSWORD" => "password" . $i,
                "U_ROLE" => $role,
                "U_ACCOUNT_STATUS" => "ACCOUNT_STATUS_ACTIVE",
                "U_LOGIN_TOKEN" => "",
                "U_LOGIN_TOKEN_EXPIRED" => "",
            ];
            self::createData($user);
        }
    }
    

    public static function getActive(){
        return self::where("U_ACCOUNT_STATUS", "ACCOUNT_STATUS_ACTIVE")->get();
    }


    public static function getInctive(){
        return self::where("U_ACCOUNT_STATUS", "ACCOUNT_STATUS_INACTIVE")->get();
    }


    public static function getUsers(){
        return self
            ::where("U_ROLE", "!=", "ROLE_SUPERADMIN")
            ->get();
    }


    public static function getByUsername($username){
        return self::where("U_USERNAME", $username)->first();
    }

    
    public static function getByLoginToken($loginToken){
        return self::where("U_LOGIN_TOKEN", $loginToken)->first();
    }


    public static function isLoginTokenValid($loginToken){
        $user = self::where("U_LOGIN_TOKEN", $loginToken)->first();
        if(!$user) return false;
        if(date("Y-m-d H:i:s") > $user->U_LOGIN_TOKEN_EXPIRED) return false;
        return true;
    }


}
