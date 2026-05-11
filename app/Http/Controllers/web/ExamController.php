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
        // WINDOW WAKTU
        // =========================================================

        $tesAmulai   = _setting::find("CBT_A_WAKTU_MULAI")->S_VALUE;
        $tesASelesai = _setting::find("CBT_A_WAKTU_SELESAI")->S_VALUE;

        $tesBmulai   = _setting::find("CBT_B_WAKTU_MULAI")->S_VALUE;
        $tesBSelesai = _setting::find("CBT_B_WAKTU_SELESAI")->S_VALUE;

        $tesAmulai   = Carbon::parse($tesAmulai);
        $tesASelesai = Carbon::parse($tesASelesai);

        $tesBmulai   = Carbon::parse($tesBmulai);
        $tesBSelesai = Carbon::parse($tesBSelesai);

        $now = Carbon::now();

        // =========================================================
        // AMBIL DATA PENGERJAAN
        // =========================================================

        $pengerjaanAkademik = mPengerjaan::where('SISWA_ID', $loginUser->siswa->SISWA_ID)
            ->where('PGRJN_JENIS', 'Akademik')
            ->first();

        $pengerjaanPsikotest = mPengerjaan::where('SISWA_ID', $loginUser->siswa->SISWA_ID)
            ->where('PGRJN_JENIS', 'Psikotest')
            ->first();

        $pengerjaan = null;

        // =========================================================
        // LOGIC PEMILIHAN TEST
        // =========================================================

        /*
        PRIORITAS:

        1. Jika waktu Akademik BELUM lewat:
        → paksa Akademik

        2. Jika waktu Akademik SUDAH lewat:
        → paksa Psikotest
        */

        if ($now->lte($tesASelesai)) {

            // =========================
            // AKADEMIK
            // =========================

            $pengerjaan = $pengerjaanAkademik;

            $windowMulai   = $tesAmulai;
            $windowSelesai = $tesASelesai;

        } else {

            // =========================
            // PSIKOTEST
            // =========================

            $pengerjaan = $pengerjaanPsikotest;

            $windowMulai   = $tesBmulai;
            $windowSelesai = $tesBSelesai;
        }

        // =========================================================
        // JIKA TIDAK ADA PENGERJAAN
        // =========================================================

        if (!$pengerjaan) {

            return response()->json([
                'success' => false,
                'message' => 'Data pengerjaan tidak ditemukan.'
            ], 422);
        }

        // =========================================================
        // JIKA SUDAH SELESAI
        // =========================================================

        if ($pengerjaan->PGRJN_SELESAI != '0000-00-00 00:00:00') {

            return response()->json([
                'success' => false,
                'finished_all' => true,
                'message' => 'Semua tes telah selesai.'
            ], 422);
        }

        // =========================================================
        // VALIDASI BELUM MULAI
        // =========================================================

        if ($now->lt($windowMulai)) {

            return response()->json([
                'success' => false,
                'waiting' => true,
                'message' => 'Tes '.$pengerjaan->PGRJN_JENIS.' belum dimulai.'
            ], 422);
        }

        // =========================================================
        // VALIDASI SUDAH LEWAT
        // =========================================================

        if ($now->gt($windowSelesai)) {

            return response()->json([
                'success' => false,
                'message' => 'Waktu tes '.$pengerjaan->PGRJN_JENIS.' telah selesai.'
            ], 422);
        }

        // =========================================================
        // TANDAI MULAI
        // =========================================================

        if ($pengerjaan->PGRJN_MULAI == "0000-00-00 00:00:00") {

            $pengerjaan->PGRJN_MULAI = Carbon::now();

            $pengerjaan->save();
        }

        // =========================================================
        // HITUNG SISA WAKTU
        // =========================================================

        $sisaDetik = max(
            0,
            $now->diffInSeconds($windowSelesai, false)
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

        $durasiMenit = 90;

        $mulai = Carbon::parse($pengerjaan->PGRJN_MULAI);

        if (
            Carbon::now()->greaterThan(
                $mulai->copy()->addMinutes($durasiMenit)
            )
        ) {

            return response()->json([
                'success' => false,
                'message' => 'Waktu habis.'
            ], 403);
        }

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
            ->with('examSoal')
            ->get();

        $totalSoal = mExam::where('EXAM_JENIS', $pengerjaan->PGRJN_JENIS)
            ->count();

        $benar = 0;

        foreach ($jawaban as $j) {

            if (
                $j->examSoal &&
                strtoupper($j->JWB_KET) === strtoupper($j->examSoal->EXAM_KUNCI)
            ) {

                $benar++;
            }
        }

        $nilai = $totalSoal > 0
            ? round(($benar / $totalSoal) * 100, 2)
            : 0;

        // =========================================================
        // UPDATE PENGERJAAN
        // =========================================================

        $pengerjaan->PGRJN_SELESAI = Carbon::now();
        $pengerjaan->PGRJN_NILAI   = $nilai;

        $pengerjaan->save();

        // =========================================================
        // CEK NEXT TEST
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
            'benar'     => $benar,
            'total'     => $totalSoal,
            'next_test' => $nextTest,
        ]);
    }
}