<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use Carbon\Carbon;
use Carbon\CarbonPeriod;

use App\Models\Provinsi as mProvinsi;
use App\Models\Kota as mKota;
use App\Models\Kecamatan as mKecamatan;
use App\Models\Kelurahan as mKelurahan;
use App\Models\Siswa as mSiswa;

use App\Services\InboxService as mInboxService;

class APIController extends Controller
{
    // CONTROLLER JUST FOR TESTING !!

    protected $inboxService;

    public function __construct(){
        $this->inboxService = new mInboxService();
    }

    
    public function tes1(Request $request){
        
        $unique = false;
        while (!$unique){
            $no = date('YmdHis').rand(1000,9999);
            if(!mSiswa::getByNo($no)){
                $unique = true;
            }
        }
        return compose("SUCCESS", "ok", $no);
    }

    public function tes2(Request $request){
        $data = Storage::url("siswa/080e8510-98dd-4011-a879-c99832e1699e.png");
        dd(Storage::disk('public')->exists("siswa/080e8510-98dd-4011-a879-c99832e1699e.png"));
        return compose("SUCCESS", "Data", $data);
    }
    
    public function tes3(Request $request){
        $siswa = mSiswa::find(1);

        $msg = "";
        $msg .= "Pendaftaran siswa baru berhasil, dengan data sebagai berikut:";
        $msg .= "\n\nNama:";
        $msg .= "\n*".$siswa->SISWA_NAMA."*";
        $msg .= "\n\nNISN:";
        $msg .= "\n*".$siswa->SISWA_NISN."*";
        $msg .= "\n\nJalur:";
        $msg .= "\n*".$siswa->refJalur->R_INFO."*";
        $msg .= "\n\n_silahkan melakukan verifikasi berkas pada tanggal yang sudah ditentukan\nterimakasih._";
        
        $this->inboxService->send([
            'U_ID' => $siswa->U_ID,
            'jenis' => "success",
            'judul' => 'Pendaftaran Siswa Berhasil',
            'isi' => $msg,
            "to" => $siswa->SISWA_WA,
        ]);

        return compose("SUCCESS", "ok");
    }

    public function tes4(Request $request){
        $data = str_pad(10, 4, '0', STR_PAD_LEFT);

        return compose("SUCCESS", "ok", $data);
    }

    public function tes5(Request $request){

    }


}
