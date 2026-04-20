<?php

namespace App\Console\Commands;

use App\Models\Schedule;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:update-schedule-message')]
#[Description('Command description')]
class UpdateScheduleMessage extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
{
    $schedule = Schedule::first();

    if ($schedule) {
        $schedule->create([
            'message' => '✅ This message was updated by Scheduler at ' . now()
        ]);
    }

    return 0;
}


}
