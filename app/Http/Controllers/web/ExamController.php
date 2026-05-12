<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;

use App\Models\Exam as mExam;
use App\Models\Pengerjaan as mPengerjaan;
use App\Models\PengerjaanJawaban as mPengerjaanJawaban;
use App\Models\_setting as _setting;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;
class ExamController extends Controller
{

    public function index(Request $request)
    {
        $req = $request->all();

        $rData = [
        ];

        return view('exam.index', $rData);
    }

    /**
     * VERIFY TOKEN + TENTUKAN TEST AKTIF
     */
    public function verifyToken(Request $request)
    {
        $loginUser = $request->loginUser;

        $request->validate([
            'token' => 'required|string'
        ]);

        $token = trim($request->token);

        $keyToken = _setting::find("CBT_TOKEN")->S_VALUE;

        if ($token != $keyToken) {

            return response()->json([
                'success' => false,
                'message' => 'token salah'
            ], 422);
        }

        // =========================================================
        // AMBIL DATA PENGERJAAN
        // =========================================================

        $pengerjaanAkademik = mPengerjaan::where('SISWA_ID', $loginUser->siswa->SISWA_ID)
            ->where('PGRJN_JENIS', 'Akademik')
            ->first();

        if(!$pengerjaanAkademik){
            $pengerjaanAkademik = mPengerjaan::create([
               "SISWA_ID" => $loginUser->siswa->SISWA_ID,
               "PGRJN_JENIS" => "Akademik"
            ]);
        }

        $pengerjaanPsikotest = mPengerjaan::where('SISWA_ID', $loginUser->siswa->SISWA_ID)
            ->where('PGRJN_JENIS', 'Psikotest')
            ->first();

        if(!$pengerjaanPsikotest){
            $pengerjaanPsikotest = mPengerjaan::create([
               "SISWA_ID" => $loginUser->siswa->SISWA_ID,
               "PGRJN_JENIS" => "Psikotest"
            ]);
        }

        $pengerjaan = null;

        // =========================================================
        // PRIORITAS TEST
        // =========================================================

        /*
        RULE:

        1. Jika Akademik belum selesai:
           → paksa Akademik

        2. Jika Akademik selesai:
           → lanjut Psikotest
        */

        if (
            $pengerjaanAkademik &&
            $pengerjaanAkademik->PGRJN_SELESAI == '0000-00-00 00:00:00'
        ) {

            $pengerjaan = $pengerjaanAkademik;

        } else {

            $pengerjaan = $pengerjaanPsikotest;
        }

        // =========================================================
        // VALIDASI PENGERJAAN
        // =========================================================

        if (!$pengerjaan) {

            return response()->json([
                'success' => false,
                'message' => 'Data pengerjaan tidak ditemukan.'
            ], 422);
        }

        // =========================================================
        // VALIDASI SUDAH SELESAI
        // =========================================================

        if ($pengerjaan->PGRJN_SELESAI != '0000-00-00 00:00:00') {

            return response()->json([
                'success' => false,
                'finished_all' => true,
                'message' => 'Semua tes telah selesai.'
            ], 422);
        }

        // =========================================================
        // DURASI TEST
        // =========================================================

        $durasiMenit = 0;

        if ($pengerjaan->PGRJN_JENIS == 'Akademik') {

            $durasiMenit = 60;

        } else {

            $durasiMenit = 30;
        }

        // =========================================================
        // TANDAI MULAI
        // =========================================================

        if ($pengerjaan->PGRJN_MULAI == "0000-00-00 00:00:00") {

            $pengerjaan->PGRJN_MULAI = Carbon::now();

            $pengerjaan->save();
        }

        // =========================================================
        // HITUNG DEADLINE
        // =========================================================

        $mulai = Carbon::parse($pengerjaan->PGRJN_MULAI);

        $deadline = $mulai->copy()->addMinutes($durasiMenit);

        $now = Carbon::now();

        // =========================================================
        // VALIDASI WAKTU HABIS
        // =========================================================

        if ($now->greaterThan($deadline)) {

            // auto finish
            $pengerjaan->PGRJN_SELESAI = $deadline;
            $pengerjaan->save();

            return response()->json([
                'success' => false,
                'message' => 'Waktu tes telah habis.'
            ], 422);
        }

        // =========================================================
        // HITUNG SISA DETIK
        // =========================================================

        $sisaDetik = max(
            0,
            $now->diffInSeconds($deadline, false)
        );

        // =========================================================
        // AMBIL SOAL
        // =========================================================

        $soal = mExam::where('EXAM_JENIS', $pengerjaan->PGRJN_JENIS)
            ->orderBy('EXAM_NO')
            ->get();

        // =========================================================
        // AMBIL JAWABAN EXISTING
        // =========================================================

        $jawabanExisting = mPengerjaanJawaban::where('PGRJN_ID', $pengerjaan->PGRJN_ID)
            ->pluck('JWB_KET', 'EXAM_ID');

        // =========================================================
        // RETURN
        // =========================================================

        return response()->json([
            'success'      => true,
            'pgrjn_id'     => $pengerjaan->PGRJN_ID,
            'jenis'        => $pengerjaan->PGRJN_JENIS,
            'sisa_detik'   => (int) $sisaDetik,
            'soal'         => $soal,
            'jawaban'      => $jawabanExisting,
            'siswa'        => $pengerjaan->siswa,
        ]);
    }

    /**
     * SIMPAN JAWABAN
     */
    public function saveAnswer(Request $request)
    {
        $request->validate([
            'pgrjn_id' => 'required',
            'exam_id'  => 'required',
            'jawaban'  => 'required|string|max:1',
        ]);

        $pengerjaan = mPengerjaan::where('PGRJN_ID', $request->pgrjn_id)
            ->where('PGRJN_SELESAI', '0000-00-00 00:00:00')
            ->first();

        if (!$pengerjaan) {
            return response()->json([
                'success' => false,
                'message' => 'Sesi tidak valid.'
            ], 403);
        }

        // =========================================================
        // DURASI TEST
        // =========================================================

        $durasiMenit = 0;

        if ($pengerjaan->PGRJN_JENIS == 'Akademik') {

            $durasiMenit = 60;

        } else {

            $durasiMenit = 30;
        }

        // =========================================================
        // VALIDASI WAKTU
        // =========================================================

        $mulai = Carbon::parse($pengerjaan->PGRJN_MULAI);

        $deadline = $mulai->copy()->addMinutes($durasiMenit);

        if (Carbon::now()->greaterThan($deadline)) {

            // auto finish
            $pengerjaan->PGRJN_SELESAI = $deadline;
            $pengerjaan->save();

            return response()->json([
                'success' => false,
                'message' => 'Waktu habis.'
            ], 403);
        }

        // =========================================================
        // SIMPAN JAWABAN
        // =========================================================

        mPengerjaanJawaban::updateOrCreate(
            [
                'PGRJN_ID' => $request->pgrjn_id,
                'EXAM_ID'  => $request->exam_id,
            ],
            [
                'JWB_KET'   => strtoupper($request->jawaban),
                'JWB_WAKTU' => Carbon::now(),
            ]
        );

        return response()->json([
            'success' => true
        ]);
    }

    /**
     * FINISH EXAM
     */
    public function finish(Request $request)
    {
        $request->validate([
            'pgrjn_id' => 'required'
        ]);

        $pengerjaan = mPengerjaan::where('PGRJN_ID', $request->pgrjn_id)
            ->where('PGRJN_SELESAI', '0000-00-00 00:00:00')
            ->firstOrFail();

        // =========================================================
        // HITUNG NILAI
        // =========================================================

        $jawaban = mPengerjaanJawaban::where('PGRJN_ID', $pengerjaan->PGRJN_ID)
            ->with('exam')
            ->get();

        $totalSoal = mExam::where('EXAM_JENIS', $pengerjaan->PGRJN_JENIS)
            ->count();

        $nilai = 0;
        foreach ($jawaban as $j) {
            $exam = $j->exam;
            $field = "EXAM_" . strtoupper($j->JWB_KET) . "_BOBOT";
            $nilai += (float) ($exam->$field ?? 0);
        }

        // =========================================================
        // UPDATE PENGERJAAN
        // =========================================================

        $pengerjaan->PGRJN_SELESAI = Carbon::now();
        $pengerjaan->PGRJN_NILAI   = $nilai;
        $pengerjaan->save();
        
        $pengerjaan->siswa->{"SISWA_TES_CBT_".strtoupper($pengerjaan->PGRJN_JENIS)} = $nilai;
        $pengerjaan->siswa->save();

        // =========================================================
        // NEXT TEST
        // =========================================================

        $nextTest = null;

        if ($pengerjaan->PGRJN_JENIS == 'Akademik') {

            $next = mPengerjaan::where('SISWA_ID', $pengerjaan->SISWA_ID)
                ->where('PGRJN_JENIS', 'Psikotest')
                ->first();

            if (
                $next &&
                $next->PGRJN_SELESAI == '0000-00-00 00:00:00'
            ) {

                $nextTest = 'Psikotest';
            }
        }

        // =========================================================
        // RETURN
        // =========================================================

        return response()->json([
            'success'   => true,
            'nilai'     => $nilai,
            'benar'     => "",
            'total'     => $totalSoal,
            'next_test' => $nextTest,
        ]);
    }
}