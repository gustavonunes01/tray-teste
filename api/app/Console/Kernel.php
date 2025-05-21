<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Para ser rodado sempre as 18:00 (6 PM)
        $schedule->command('sells:notify-seller-daily')
                ->dailyAt('18:00')
                ->timezone('America/Sao_Paulo');

        $schedule->command('sells:notify-admin-daily')
                ->dailyAt('18:00')
                ->timezone('America/Sao_Paulo');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
