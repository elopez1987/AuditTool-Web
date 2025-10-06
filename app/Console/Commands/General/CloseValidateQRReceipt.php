<?php

namespace App\Console\Commands\General;

use Carbon\Carbon;
use DB;
use Illuminate\Console\Command;

class CloseValidateQRReceipt extends Command
{
    protected $signature = 'command:CloseValidateQRReceipt';

    protected $description = 'Cierra el tiempo de valides de los codigo QR generados para el circito de la planta';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        DB::table('storageaccesswarehouse')->where('statusid', '=', 'AC')
            ->where('datefinish', '<', Carbon::now())->update(['statusid' => 'BK']);
    }
}
