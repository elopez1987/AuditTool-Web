<?php

namespace App\Console\Commands\General;

use App\Models\Receipt\ReceiptAsn;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ClearReceiptAsnCommand extends Command
{
    protected $signature = 'clear:receiptasn';

    protected $description = 'Command description';

    public function handle(): void
    {
        ReceiptAsn::where('statusid' , 'PG')
            ->where('addasndate', '<', Carbon::now()->subDays(10))
            ->update(['statusid' => 'CE']);
    }


}
