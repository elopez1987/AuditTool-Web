<?php

namespace App\Console\Commands\Receipt;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MoveReceiptToSecondTurn extends Command
{
    protected $signature = 'command:MoveReceiptToSecondTurn';

    protected $description = 'Mueve los recibos que no se terminaron del primero al segundo';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        DB::table('receiptasn')->whereDate('workday', Carbon::now()->format('Y-m-d'))
            ->where('turnid', '=', '1')
            ->whereNotIn('statusid', ['IG', 'CE'])
            ->update([
                'turnid' => 2,
                'auxiliary10' => 'Y',
            ]);
    }
}
