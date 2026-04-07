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
                "ROLE_ADMIN_BERITA" => "profil",
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
                "ROLE_ADMIN_BERITA" => "profil",
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
                'status' => "ACCOUNT_STATUS_ACTIVE",
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

    public function doUpdate(Request $request){
        $req = $request->all();

        try{
            $response = $this->mAPIauth->update($request);
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

        return redirect()->back()->with("success", $message);

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


    public function admin(Request $request){
        $req = $request->all();

        $refRole = getReferences("ROLE");
        unset($refRole["ROLE_SUPERADMIN"]);

        $viewData = [
            "admin" => mUser::getUsers(),
            "refRole" => $refRole
        ];
        //return view("admin.manage.index", $viewData);
        return view("admin.manage.index-datatable", $viewData);
    }


    public function datatable(Request $request)
    {
        // ── Ambil semua admin (non-siswa) ──────────────────────────────────
        $query = \DB::table('_user')  // sesuaikan nama tabel
            ->whereNotIn('U_ROLE', ['ROLE_SUPERADMIN'])
            ->select('U_ID', 'U_USERNAME', 'U_ROLE', 'U_ACCOUNT_STATUS', 'U_LOGIN_LAST');
 
        // ── Filter role (dari dropdown filter) ────────────────────────────
        if ($request->filled('role') && $request->role !== 'all') {
            $query->where('U_ROLE', $request->role);
        }
 
        // ── DataTable server-side params ───────────────────────────────────
        $totalRecords = $query->count();
 
        // Search global
        if ($request->filled('search.value')) {
            $search = $request->input('search.value');
            $query->where(function ($q) use ($search) {
                $q->where('U_USERNAME', 'like', "%{$search}%")
                  ->orWhere('U_ROLE', 'like', "%{$search}%");
            });
        }
 
        $filteredRecords = $query->count();
 
        // Sorting
        $orderCol   = $request->input('order.0.column', 0);
        $orderDir   = $request->input('order.0.dir', 'asc');
        $columns    = ['U_ID', 'U_USERNAME', 'U_ROLE', 'U_ACCOUNT_STATUS', 'U_LOGIN_LAST'];
        $sortColumn = $columns[$orderCol] ?? 'U_ID';
        $query->orderBy($sortColumn, $orderDir);
 
        // Pagination
        $start  = (int) $request->input('start', 0);
        $length = (int) $request->input('length', 10);
        $users  = $query->offset($start)->limit($length)->get();
 
        // ── Format data untuk DataTable ────────────────────────────────────
        $roleLabels = [
            'ROLE_ADMIN_BERITA' => ['label' => 'Admin Berita',      'class' => 'bg-violet-100 text-violet-700'],
            'ROLE_ADMIN_BERKAS' => ['label' => 'Admin Verifikasi',  'class' => 'bg-sky-100 text-sky-700'],
            'ROLE_SISWA'        => ['label' => 'Siswa',             'class' => 'bg-blue-100 text-blue-700'],
        ];
 
        $data = $users->map(function ($u, $i) use ($roleLabels, $start) {
            $role    = $roleLabels[$u->U_ROLE] ?? ['label' => $u->U_ROLE, 'class' => 'bg-gray-100 text-gray-500'];
            $isActive = $u->U_ACCOUNT_STATUS === 'ACCOUNT_STATUS_ACTIVE';
 
            $avatarLetter = strtoupper(substr($u->U_USERNAME, 0, 1));
 
            $usernameHtml = "
                <div class='flex items-center gap-3'>
                    <div class='w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs'>
                        {$avatarLetter}
                    </div>
                    <span class='font-medium text-gray-800'>{$u->U_USERNAME}</span>
                </div>
            ";
 
            $roleHtml = "
                <span class='inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {$role['class']}'>
                    {$role['label']}
                </span>
            ";
 
            $dot      = $isActive ? 'bg-emerald-500' : 'bg-gray-300';
            $txtClass = $isActive ? 'text-emerald-600' : 'text-gray-400';
            $label    = $isActive ? 'Aktif' : 'Non-aktif';
            $statusHtml = "
                <span class='inline-flex items-center gap-1.5 text-xs font-medium {$txtClass}'>
                    <span class='w-1.5 h-1.5 rounded-full {$dot}'></span>
                    {$label}
                </span>
            ";
 
            $loginLast = $u->U_LOGIN_LAST ?? '<span class="text-gray-300">Belum pernah</span>';
 
            $actionHtml = "
                <div class='flex items-center justify-center gap-2'>
                    <button onclick='openEdit({$u->U_ID})'
                        class='p-1.5 rounded-lg text-blue-500 hover:bg-blue-50 transition' title='Edit'>
                        <svg class='w-4 h-4' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                            <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2'
                                d='M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 112.828 2.828L11.828 15.828a2 2 0 01-1.414.586H9v-2a2 2 0 01.586-1.414z'/>
                        </svg>
                    </button>
                    <button onclick='deleteUser({$u->U_ID})'
                        class='p-1.5 rounded-lg text-red-400 hover:bg-red-50 transition' title='Hapus'>
                        <svg class='w-4 h-4' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                            <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2'
                                d='M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0V5a1 1 0 011-1h4a1 1 0 011 1v2M4 7h16'/>
                        </svg>
                    </button>
                </div>
            ";
 
            return [
                'no'         => $start + $i + 1,
                'username'   => $usernameHtml,
                'role'       => $roleHtml,
                'status'     => $statusHtml,
                'login_last' => $loginLast,
                'aksi'       => $actionHtml,
                // Data mentah untuk keperluan edit
                '_raw' => [
                    'id'       => $u->U_ID,
                    'username' => $u->U_USERNAME,
                    'role'     => $u->U_ROLE,
                    'status'   => $u->U_ACCOUNT_STATUS,
                ],
            ];
        });
 
        return response()->json([
            'draw'            => (int) $request->input('draw', 1),
            'recordsTotal'    => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data'            => $data,
        ]);
    }

    
}
