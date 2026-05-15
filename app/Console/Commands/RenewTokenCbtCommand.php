<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use App\Models\_setting;

class RenewTokenCbtCommand extends Command
{
    protected $signature = 'token:rotate';
    protected $description = 'Rotate CBT token every 30 minutes';

    public function handle(): int
    {
        while (true) {

            $newToken = strtoupper(Str::random(6));

            _setting::find("CBT_TOKEN")->update([
                "S_VALUE" => $newToken
            ]);

            $this->info(now() . " Token updated: " . $newToken);

            // tidur 30 menit
            sleep(1800);
        }

        return self::SUCCESS;
    }
}