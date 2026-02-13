<?php

namespace App\Jobs;

use App\Models\PengerjaanJawaban;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class KoreksiEssayJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $jawabanId;
    public $userId;

    public $tries = 3;
    public $backoff = 5;
    public $timeout = 120;

    public function __construct(int $jawabanId, int $userId)
    {
        $this->jawabanId = $jawabanId;
        $this->userId = $userId;
    }

    public function handle()
    {
        $jawaban = PengerjaanJawaban::with([
            'examSoal',
            'pengerjaan.exam'
        ])->find($this->jawabanId);

        if (!$jawaban) {
            return;
        }

        $user = User::find($this->userId);
        if (!$user) {
            return;
        }

        Http::timeout(60)
            ->retry(3, 5000)
            ->post(config('app.AIbaseURL') . '/digital-school/koreksi', [
                'login_token'    => $user->U_LOGIN_TOKEN,
                'jawaban_id'     => $jawaban->JWB_ID,
                'pertanyaan'     => $jawaban->examSoal->SOAL_KET,
                'jawaban_benar'  => $jawaban->examSoal->SOAL_JAWABAN,
                'jawaban_siswa'  => $jawaban->JWB_KET,
                'max_nilai'      => $jawaban->pengerjaan->exam->EXAM_BOBOT_ESSAY,
            ]);
    }

    public function failed(\Throwable $e)
    {
        \Log::error('Koreksi essay gagal', [
            'jawaban_id' => $this->jawabanId,
            'error' => $e->getMessage(),
        ]);
    }

}
