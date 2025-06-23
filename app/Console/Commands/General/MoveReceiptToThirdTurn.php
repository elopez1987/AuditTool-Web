<?php

namespace App\Console\Commands\General;

use Carbon\Carbon;
use DB;
use Illuminate\Console\Command;

class MoveReceiptToThirdTurn extends Command
{

    protected $signature = 'command:MoveReceiptToThirdTurn';
    protected $description = 'Mueve los recibos sin terminar del segundo turno al tercero';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        DB::table('receiptasn')->whereDate('workday', Carbon::now()->format('Y-m-d'))
            ->where('turnid', '=', 2)
            ->whereNotIn('statusid', ['IG', 'CE'])
            ->update(['turnid' => 3,
                'auxiliary10' => 'Y',
            ]);
    }
}
