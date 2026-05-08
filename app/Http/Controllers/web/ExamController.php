<?php

namespace App\Http\Controllers\web;
use App\Http\Controllers\Controller;

use App\Models\Exam as mExam;
use App\Models\Pengerjaan as mPengerjaan;
use App\Models\PengerjaanJawaban as mPengerjaanJawaban;

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
            "exam" => mExam::where("EXAM_JENIS", $req["jenis"]),
        ];
        return view('exam.index', $rData);
    }


    /**
     * Verifikasi token dan mulai sesi ujian
     * Token format bebas — sesuaikan dengan sistem token Anda.
     * Di sini token diasumsikan = PGRJN_ID yang valid dan belum dikerjakan.
     */
    public function verifyToken(Request $request)
    {
        $loginUser = $request->loginUser;

        $request->validate(['token' => 'required|string']);
    
        $token = trim($request->token);
    
        // Cari pengerjaan berdasarkan token (PGRJN_ID atau kolom token custom)
        // Sesuaikan query ini dengan logika token aplikasi Anda

        if ($token != "DEF456") {
            return response()->json([
                'success' => false,
                'message' => 'token salah.'
            ], 422);
        }

        $pengerjaan = mPengerjaan::where('SISWA_ID', $loginUser->siswa->SISWA_ID)
            ->where('PGRJN_SELESAI', '0000-00-00 00:00:00')   // belum selesai
            ->first();
    
        if (!$pengerjaan) {
            return response()->json([
                'success' => false,
                'message' => 'ujian sudah selesai.'
            ], 422);
        }
    
        // Tandai mulai jika belum
        if ($pengerjaan->PGRJN_MULAI = "0000-00-00 00:00:00") {
            $pengerjaan->PGRJN_MULAI = Carbon::now();
            $pengerjaan->save();
        }
    
        // Ambil soal sesuai jenis
        $soal = mExam::where('EXAM_JENIS', $pengerjaan->PGRJN_JENIS)
            ->orderBy('EXAM_NO')
            ->get();
    
        // Ambil jawaban yang sudah diisi (jika ada)
        $jawabanExisting = mPengerjaanJawaban::where('PGRJN_ID', $pengerjaan->PGRJN_ID)
            ->pluck('JWB_KET', 'EXAM_ID');
    
        // Hitung sisa waktu (asumsi durasi 90 menit)
        $durasiMenit = 90;
        $mulai = Carbon::parse($pengerjaan->PGRJN_MULAI);
        $selesaiAt = $mulai->copy()->addMinutes($durasiMenit);
        $sisaDetik = max(0, Carbon::now()->diffInSeconds($selesaiAt, false));
        $sisaDetik = 1000;
    
        // Jika waktu habis, kembalikan error
        if ($sisaDetik <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Waktu ujian sudah habis.'
            ], 422);
        }
    
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
     * Simpan jawaban per soal (dipanggil setiap kali jawaban berubah)
     */
    public function saveAnswer(Request $request)
    {
        $request->validate([
            'pgrjn_id' => 'required',
            'exam_id'  => 'required',
            'jawaban'  => 'required|string|max:1',
        ]);
    
        // Validasi sesi masih aktif
        $pengerjaan = mPengerjaan::where('PGRJN_ID', $request->pgrjn_id)
            ->where('PGRJN_SELESAI', '0000-00-00 00:00:00')
            ->first();
    
        if (!$pengerjaan) {
            return response()->json(['success' => false, 'message' => 'Sesi tidak valid.'], 403);
        }
    
        // Cek waktu
        $durasiMenit = 90;
        $mulai = Carbon::parse($pengerjaan->PGRJN_MULAI);
        if (Carbon::now()->greaterThan($mulai->copy()->addMinutes($durasiMenit))) {
            return response()->json(['success' => false, 'message' => 'Waktu habis.'], 403);
        }
    
        // Upsert jawaban
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
    
        return response()->json(['success' => true]);
    }
    
    /**
     * Selesaikan ujian & hitung nilai
     */
    public function finish(Request $request)
    {
        $request->validate(['pgrjn_id' => 'required']);
    
        $pengerjaan = mPengerjaan::where('PGRJN_ID', $request->pgrjn_id)
            ->where('PGRJN_SELESAI', '0000-00-00 00:00:00')
            ->firstOrFail();
    
        // Hitung nilai
        $jawaban = mPengerjaanJawaban::where('PGRJN_ID', $pengerjaan->PGRJN_ID)
            ->with('examSoal')
            ->get();
    
        $totalSoal = mExam::where('EXAM_JENIS', $pengerjaan->PGRJN_JENIS)->count();
        $benar = 0;
    
        foreach ($jawaban as $j) {
            if ($j->examSoal && strtoupper($j->JWB_KET) === strtoupper($j->examSoal->EXAM_KUNCI)) {
                $benar++;
            }
        }
    
        $nilai = $totalSoal > 0 ? round(($benar / $totalSoal) * 100, 2) : 0;
    
        $pengerjaan->PGRJN_SELESAI = Carbon::now();
        $pengerjaan->PGRJN_NILAI   = $nilai;
        $pengerjaan->save();
    
        return response()->json([
            'success' => true,
            'nilai'   => $nilai,
            'benar'   => $benar,
            'total'   => $totalSoal,
        ]);
    }




}