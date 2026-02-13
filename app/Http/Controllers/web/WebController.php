<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use ZipArchive;

use App\Models\Absen as mAbsen;
use App\Models\Guru as mGuru;
use App\Models\Hari as mHari;
use App\Models\Kelas as mKelas;
use App\Models\Jadwal as mJadwal;
use App\Models\JadwalMapel as mJadwalMapel;
use App\Models\Mapel as mMapel;
use App\Models\Pembelajaran as mPembelajaran;
use App\Models\Semester as mSemester;
use App\Models\Siswa as mSiswa;
use App\Models\User as mUser;
use App\Models\Proyek as mProyek;
use App\Models\ProyekSub as mProyekSub;
use App\Models\ProyekSubPenilaian as mProyekSubPenilaian;
use App\Models\Exam as mExam;
use App\Models\ExamSoal as mExamSoal;
use App\Models\Pengerjaan as mPengerjaan;
use App\Models\PengerjaanJawaban as mPengerjaanJawaban;


class WebController extends Controller
{
    public function home(Request $request){
        $viewData = [
            "totalSiswa" => count(mSiswa::all()) - 5 - 3,
            "totalGuru" => count(mGuru::all()) - 3,
            "totalKelas" => count(mKelas::all()) - 1,
        ];
        return view("home", $viewData);
    }

    public function monitor(Request $request){
        $loginUser = $request->loginUser; // login user
        $req = $request->all();

        $siswa = mSiswa::find($loginUser->SISWA_ID);

        $tglAwal = date("Y-m-d");
        $tglAkhir = date("Y-m-d");
        // testing
        // $tglAwal = "2025-11-10";
        // $tglAkhir = "2025-11-10";
        if(isset($req["tglAwal"])) $tglAwal = $req["tglAwal"];
        if(isset($req["tglAkhir"])) $tglAkhir = $req["tglAkhir"];


        $hari = mHari::getByPeriode($tglAwal, $tglAkhir);
        $dataPembelajaran = [];

        foreach ($hari as $_hari) {

            $tgl = $_hari["HARI_ID"];
            $namaHari = $_hari->refNamaHari->R_INFO;

            // dapatkan semua pembelajaran di tanggal ini
            $pembelajaranList = mPembelajaran::getByPeriode($tgl, $tgl, $siswa->KELAS_ID, null);

            $tugasLatihanPerHari = [];

            // --- loop pembelajaran dulu
            foreach ($pembelajaranList as $pembelajaran) {

                $tugasLatihan = [];

                foreach ($pembelajaran->exam as $exam) {
                    $pengerjaan = mPengerjaan::getByExamAndSiswa($exam->EXAM_ID,$siswa->SISWA_ID);

                    $tugasLatihan[] = [
                        "EXAM" => $exam,
                        "PENGERJAAN" => $pengerjaan
                    ];
                }

                $absensi = mAbsen::getByPembelajaranSiswa($pembelajaran->PBL_ID, $siswa->SISWA_ID);

                $tugasLatihanPerHari[] = [
                    "PEMBELAJARAN" => $pembelajaran,
                    "ABSENSI" => $absensi,
                    "TUGAS_LATIHAN" => $tugasLatihan
                ];
            }

            // simpan rekap harian
            $dataPembelajaran[] = [
                "TGL" => $tgl,
                "HARI" => $namaHari,
                "DATA" => $tugasLatihanPerHari
            ];
        }


        $viewData = [
            "user" => $loginUser,
            "siswa" => $siswa,
            "tglAwal" => $tglAwal,
            "tglAkhir" => $tglAkhir,
            "dataPembelajaran" => $dataPembelajaran,
        ];
        if(isset($req["json"])) dd($viewData);
        return view("monitor", $viewData);
    }

    public function showFile($filename, Request $req)
    {
        if(!isset($req["path"])) return compose("ERROR", "parameter incomplete 'path'");
        if(!isset($req["return"])) return compose("ERROR", "parameter incomplete 'return'");
        $fullPath = $req["path"] . "/" .  $filename;

        if (!Storage::disk('public')->exists($fullPath)) {
            abort(404, 'File tidak ditemukan');
        }

        // return file (bisa ditampilkan atau diunduh tergantung header)
        if($req["return"] == "view"){
            return response()->file(storage_path('app/public/' . $fullPath));
        }
        // download
        return response()->download(storage_path('app/public/' . $fullPath));
    }

    public function downloadZIP(Request $req)
    {
        // $fileList = [
        //     "public/ss1_base64_1.txt",
        //     "public/ss1_base64_2.txt",
        //     "public/ss1_base64_3.txt",
        //     "public/ss1_base64_4.txt",
        //     "public/ss1_base64_5.txt",
        // ];

        $fileList = [
            "public/ss1.json",
            "public/ss2.json",
        ];

        $zipName = "download_" . time() . ".zip";

        return response()->streamDownload(function () use ($fileList) {
            // 1. Buat file temporary ZIP
            $tempZip = tempnam(sys_get_temp_dir(), 'zip_');

            $zip = new \ZipArchive();
            $zipStatus = $zip->open($tempZip, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

            if ($zipStatus !== true) {
                echo "Gagal membuat ZIP";  // debug
                return;
            }
            // 2. Tambahkan file-file ke ZIP
            foreach ($fileList as $path) {

                $fullPath = storage_path('app/public/' . $path);

                if (file_exists($fullPath)) {
                    $zip->addFile($fullPath, basename($path));
                }
            }
            // 3. Tutup ZIP
            $zip->close();
            // 4. Output ke user
            readfile($tempZip);
            // 5. Hapus file temp (opsional)
            unlink($tempZip);
        }, $zipName);
    }



}
