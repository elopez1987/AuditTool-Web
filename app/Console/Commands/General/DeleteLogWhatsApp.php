<?php

namespace App\Console\Commands\General;

use App\Models\Yard\LogMessageWhatsApp;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DeleteLogWhatsApp extends Command
{
    protected $signature = 'whatsapp:deletelog';

    protected $description = 'Borra los registros de la tabla logsmessagewhatsapp que sean mayores a 90 dias de la creacion';

    public function __construct()
    {
        parent::__construct();
    }
    public function handle()
    {
        $ninetyDaysAgo = Carbon::now()->subDays(30);
        LogMessageWhatsApp::where('created_at', '<', $ninetyDaysAgo)->delete();
    }
}
