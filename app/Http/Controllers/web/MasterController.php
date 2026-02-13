<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

use App\Models\Absen as mAbsen;
use App\Models\Guru as mGuru;
use App\Models\Hari as mHari;
use App\Models\Jadwal as mJadwal;
use App\Models\Jampel as mJampel;
use App\Models\JadwalMapel as mJadwalMapel;
use App\Models\Mapel as mMapel;
use App\Models\Kelas as mKelas;
use App\Models\Pembelajaran as mPembelajaran;
use App\Models\Semester as mSemester;
use App\Models\Siswa as mSiswa;
use App\Models\User as mUser;
use App\Models\Ujian as mUjian;
use App\Models\Proyek as mProyek;
use App\Models\ProyekSub as mProyekSub;
use App\Models\Exam as mExam;

use App\Http\Controllers\API\AuthController as mAPIauthController;

class MasterController extends Controller
{

    public $mAPIauth;

    public function __construct(){
        $this->mAPIauth = new mAPIauthController();
    }

    //
    public function user(Request $request){
        $req = $request->all();

        $user = mUser::all();

        $viewData = [
            'user' => $user,
        ];
        return view('master.user', $viewData);
    }

    public function userDetail(Request $request, $id){
        $req = $request->all();

        $user = null;
        if($id != "0"){
            $user = mUser::find($id);
        }
        $refRole = getReferences("ROLE");
        $refStatus = getReferences("ACCOUNT_STATUS");

        $viewData = [
            "user" => $user,
            "refRole" => $refRole,
            "refStatus" => $refStatus,
        ];
        return view('master.user-detail', $viewData);
    }

    //
    public function guru(Request $request){
        $req = $request->all();

        $guru = mGuru::all();

        $viewData = [
            'guru' => $guru,
        ];
        return view('master.guru', $viewData);
    }

    public function guruDetail(Request $request, $id){
        $req = $request->all();

        $guru = null;
        if($id != "0"){
            $guru = mGuru::find($id);
        }
        $refUser = mUser::where("U_ROLE", "ROLE_GURU")->get();
        $refJabatan = getReferences("JABATAN");
        $refJK = getReferences("JENIS_KELAMIN");

        $viewData = [
            "guru" => $guru,
            "refUser" => $refUser,
            "refJabatan" => $refJabatan,
            "refJK" => $refJK,
        ];
        return view('master.guru-detail', $viewData);
    }
    
    //
    public function siswa(Request $request){
        $req = $request->all();

        $siswa = mSiswa::all();

        $viewData = [
            'siswa' => $siswa,
        ];
        return view('master.siswa', $viewData);
    }

    public function siswaDetail(Request $request, $id){
        $req = $request->all();

        $siswa = null;
        if($id != "0"){
            $siswa = mSiswa::find($id);
        }
        $refUser = mUser::where("U_ROLE", "ROLE_SISWA")->get();
        $refKelas = mKelas::all();
        $refStatus = getReferences("STATUS_SISWA");
        $refJK = getReferences("JENIS_KELAMIN");

        $viewData = [
            "siswa" => $siswa,
            "refUser" => $refUser,
            "refKelas" => $refKelas,
            "refStatus" => $refStatus,
            "refJK" => $refJK,
        ];
        return view('master.siswa-detail', $viewData);
    }

    //
    public function kelas(Request $request){
        $req = $request->all();

        $kelas = mKelas::all();

        $viewData = [
            'kelas' => $kelas,
        ];
        return view('master.kelas', $viewData);
    }

    public function kelasDetail(Request $request, $id){
        $req = $request->all();

        $kelas = null;
        if($id != "0"){
            $kelas = mKelas::find($id);
        }
        $refGuru = mGuru::all();
        $refSemester = mSemester::all();

        $viewData = [
            "kelas" => $kelas,
            "refGuru" => $refGuru,
            "refSemester" => $refSemester,
        ];
        return view('master.kelas-detail', $viewData);
    }

    //
    public function mapel(Request $request){
        $req = $request->all();

        $mapel = mMapel::all();

        $viewData = [
            'mapel' => $mapel,
        ];
        return view('master.mapel', $viewData);
    }

    public function mapelDetail(Request $request, $id){
        $req = $request->all();

        $mapel = null;
        if($id != "0"){
            $mapel = mMapel::find($id);
        }

        $viewData = [
            "mapel" => $mapel,
        ];
        return view('master.mapel-detail', $viewData);
    }

    //
    public function jadwal(Request $request){
        $req = $request->all();

        $jadwal = mJadwal::all();

        $viewData = [
            'jadwal' => $jadwal,
        ];
        if(isset($req["json"])) dd($jadwal);
        return view('master.jadwal', $viewData);
    }

    public function jadwalDetail(Request $request, $id){
        $req = $request->all();

        $jadwal = null;
        if($id != "0"){
            $jadwal = mJadwal::find($id);
        }
        $refKelas = mKelas::all();
        $refHari = getReferences("NAMA_HARI");
        $refJampel = mJampel::getUnusedByJadwal($id);
        $refMapel = mMapel::all();
        $refGuru = mGuru::all();

        $viewData = [
            "jadwal" => $jadwal,
            "refKelas" => $refKelas,
            "refHari" => $refHari,
            "refJampel" => $refJampel,
            "refMapel" => $refMapel,
            "refGuru" => $refGuru,
        ];
        return view('master.jadwal-detail', $viewData);
    }

    //
    public function proyek(Request $request){
        $req = $request->all();

        $proyek = mProyek::all();

        $viewData = [
            'proyek' => $proyek,
        ];
        return view('master.proyek', $viewData);
    }

    public function proyekDetail(Request $request, $id){
        $req = $request->all();

        $proyek = null;
        if($id != "0"){
            $proyek = mProyek::find($id);
        }

        $viewData = [
            "proyek" => $proyek,
        ];
        return view('master.proyek-detail', $viewData);
    }

    //
    public function ujian(Request $request){
        $req = $request->all();
        
        $filterJenis = "_ALL_";
        $filterKelas = "_ALL_";
        $filterMapel = "_ALL_";
        if(isset($req["jenis"])) $filterJenis = $req["jenis"];
        if(isset($req["kelas"])) $filterJenis = $req["kelas"];
        if(isset($req["mapel"])) $filterJenis = $req["mapel"];

        $ujian = mUjian::getByJenisKelasMapel($filterJenis, $filterKelas, $filterMapel);

        $viewData = [
            'ujian' => $ujian,
        ];
        return view('master.ujian', $viewData);
    }

    public function ujianDetail(Request $request, $id){
        $req = $request->all();

        $ujian = mUjian::find($id);

        $viewData = [
            "ujian" => $ujian,
            "exam" => mExam::where("UJN_ID", $id)->first(),
        ];
        //dd($ujian->exam);
        return view('master.ujian-detail', $viewData);
    }

}
