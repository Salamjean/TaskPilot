<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Attendance;
use Carbon\Carbon;

class AutoClockOut extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:auto-clock-out';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically clock out personnel at 17:00 if they forgot to do so';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today()->toDateString();

        $attendances = Attendance::where('date', $today)
            ->whereNotNull('clock_in')
            ->whereNull('clock_out')
            ->get();

        $count = 0;
        foreach ($attendances as $attendance) {
            $attendance->update([
                'clock_out' => '17:00:00',
            ]);
            $count++;
        }

        $this->info("Auto clock out completed. {$count} attendance(s) updated.");
    }
}
