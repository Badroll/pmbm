<?php

namespace App\Services;

use App\Models\Inbox as mInbox;
use App\Models\Siswa as mSiswa;

use Illuminate\Support\Str;

class InboxService
{
    public function send(array $data)
    {
        $inbox = mInbox::create([
            'U_ID' => $data['U_ID'],
            'INBOX_WAKTU_BUAT' => now(),
            'INBOX_JENIS' => $data['jenis'],
            'INBOX_JUDUL' => $data['judul'],
            'INBOX_ISI' => $data['isi'],
        ]);

        $siswa = mSiswa::getByUserId($data["U_ID"]);

        $msg = "*" . strtoupper($data['judul']) . "*";
        $msg .= "\n----------------------------------------------------\n\n";
        $msg .= $data['isi'];

        if($data['to'] ?? false){
            $this->sendWhatsapp($data['to'], $msg,
                function ($res) use ($inbox) {
                    logcmd("callback:", $res);
                    $inbox->update([
                        "INBOX_WAKTU_KIRIM" => now()
                    ]);
                }
            );
        }

        return $inbox;
    }

    private function sendWhatsapp($to, $msg, callable $callback = null){
        // TO DO : kirim WA
        if ($callback) {
            $callback("ok");
        }
    }

}