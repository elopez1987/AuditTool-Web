<?php

namespace App\Console\Commands\Receipt;

use App\Models\Receipt\ReceiptAsn;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ClearReceiptAsnCommand extends Command
{
    protected $signature = 'clear:receiptasn';

    protected $description = 'Limpiar las descargas programadas que no fueron procesadas en los últimos 10 días';

    public function handle(): void
    {
        ReceiptAsn::where('statusid', 'PG')
            ->where('addasndate', '<', Carbon::now()->subDays(10))
            ->update(['statusid' => 'CE']);
    }
}
