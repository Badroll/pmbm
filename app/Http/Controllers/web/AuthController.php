<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

use App\Models\Siswa as mSiswa;
use App\Models\User as mUser;

use App\Http\Controllers\API\AuthController as mAPIauthController;

class AuthController extends Controller
{

    public $mAPIauth;

    public function __construct(){
        $this->mAPIauth = new mAPIauthController();
    }


    public function login(Request $request){
        $req = $request->all();

        if(Session::has("SESSION_U_ID")){
            $nama = Session::get("SESSION_U_USERNAME");
            $role = Session::get("SESSION_U_ROLE");
            $redirectRole = [
                "ROLE_SISWA" => "/",
                "ROLE_SUPERADMIN" => "profil",
                "ROLE_ADMIN_PMBM" => "profil",
                "ROLE_ADMIN_BERKAS" => "profil",
            ];
            return redirect($redirectRole[$role])->with("success", "Selamat datang");
        }

        $viewData = [];
        return view("login", $viewData);
    }

    public function register(Request $request){
        $req = $request->all();

        if(Session::has("SESSION_U_ID")){
            $nama = Session::get("SESSION_U_USERNAME");
            $role = Session::get("SESSION_U_ROLE");
            $redirectRole = [
                "ROLE_SISWA" => "/",
                "ROLE_SUPERADMIN" => "profil",
                "ROLE_ADMIN_PMBM" => "profil",
                "ROLE_ADMIN_BERKAS" => "profil",
            ];
            return redirect($redirectRole[$role])->with("success", "Anda sudah login");
        }

        $viewData = [];
        return view("register", $viewData);
    }


    public function doLogin(Request $request){
        $req = $request->all();

        if(!isset($req["username"])) return compose("ERROR", "Parameter tidak lengkap 'username'");
        $username = $req["username"];

        if(!isset($req["password"])) return compose("ERROR", "Parameter tidak lengkap 'password'");
        $password = $req["password"];

        try{
            $response = $this->mAPIauth->login(new Request([
                'username' => $username,
                'password' => $password,
            ]));
            $response = $response->getData(true);
        } catch(Exception $e){
            return redirect()->back()->with("error", "Maaf, terjadi kesalahan", $e);
        }

        $status = $response["STATUS"];
        $message = $response["MESSAGE"];
        $payload = $response["PAYLOAD"];
        if($status == "ERROR"){
            return redirect()->back()->with("error", $message);
        }

        $this->setLoginSession($payload);

        return $this->login($request);

    }

    
    public function doRegister(Request $request){
        $req = $request->all();

        if(!isset($req["username"])) return compose("ERROR", "Parameter tidak lengkap 'username'");
        $username = $req["username"];

        if(!isset($req["password"])) return compose("ERROR", "Parameter tidak lengkap 'password'");
        $password = $req["password"];

        if(!isset($req["password_confirmation"])) return compose("ERROR", "Parameter tidak lengkap 'password'");
        $password_confirmation = $req["password_confirmation"];

        if($password != $password_confirmation){
            return redirect()->back()->with("error", "Password konfirmasi tidak sama");
        }

        try{
            $response = $this->mAPIauth->register(new Request([
                'username' => $username,
                'password' => $password,
                'role' => "ROLE_SISWA",
            ]));
            $response = $response->getData(true);
        } catch(Exception $e){
            return redirect()->back()->with("error", "Maaf, terjadi kesalahan", $e);
        }

        $status = $response["STATUS"];
        $message = $response["MESSAGE"];
        $payload = $response["PAYLOAD"];
        if($status == "ERROR"){
            return redirect()->back()->with("error", $message);
        }

        return $this->doLogin(new Request([
            'username' => $username,
            'password' => $password,
        ]));

    }


    private function setLoginSession($data){
        Session::put("SESSION_U_ID", $data["U_ID"]);
        Session::put("SESSION_U_USERNAME", $data["U_USERNAME"]);
        Session::put("SESSION_U_EMAIL", $data["U_EMAIL"]);
        Session::put("SESSION_U_PHONE", $data["U_PHONE"]);
        Session::put("SESSION_U_ROLE", $data["U_ROLE"]);
        Session::put("SESSION_U_ACCOUNT_STATUS", $data["U_ACCOUNT_STATUS"]);
        Session::put("SESSION_U_LOGIN_TOKEN", $data["U_LOGIN_TOKEN"]);
        // if($data["siswa"]){
        //     $siswa = $data["siswa"];
        //     Session::put("SESSION_SISWA_ID", $siswa["SISWA_ID"]);
        //     Session::put("SESSION_SISWA_NAMA", $siswa["SISWA_NAMA"]);
        // }
    }

    public function logout(Request $request, $msg = "Logged out"){
        Session::flush();
        if (!$request->ajax()) {
            return redirect("auth/login")->with("info", $msg)->withInput();
        } 
		else {
            return compose("ERROR", $msg);
        }
    }


    public function profil(Request $request){
        $req = $request->all();

        $viewData = [
            "siswa" => mSiswa::getByUserId(Session::get("SESSION_U_ID"))
        ];
        return view("profil", $viewData);
    }

    
}
