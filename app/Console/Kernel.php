<?php

namespace App\Console;

use App\Console\Commands\General\ClearLocationCommand;
use App\Console\Commands\General\DeleteHistLogWebServiceIzel;
use App\Console\Commands\General\DeleteLogWhatsApp;
use App\Console\Commands\Receipt\ClearReceiptAsnCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        DeleteHistLogWebServiceIzel::class,
        DeleteLogWhatsApp::class,
        ClearReceiptAsnCommand::class,
        ClearLocationCommand::class,

    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        //      Comandos generales
        $schedule->command('command:deletehistlogwebserviceizel')->weekly();
        $schedule->command('command:CloseValidateQRReceipt')->everyTwoHours();
        $schedule->command('whatsapp:deletelog')->daily();

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
