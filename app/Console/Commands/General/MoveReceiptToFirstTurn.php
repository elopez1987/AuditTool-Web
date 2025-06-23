<?php

namespace App\Console\Commands\General;

use Carbon\Carbon;
use DB;
use Illuminate\Console\Command;

class MoveReceiptToFirstTurn extends Command
{

    protected $signature = 'command:MoveReceiptToFirstTurn';

    protected $description = 'Mueve los recibos sin terminar del tercer turno al primero';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        DB::table('receiptasn')->whereDate('workday', Carbon::now()->subDay()->format('Y-m-d'))
            ->where('turnid', '=', 3)
            ->whereNotIn('statusid', ['IG', 'CE'])
            ->update(
                ['turnid' => 1,
                    'auxiliary10' => 'Y',
                ]
            );
    }
}
