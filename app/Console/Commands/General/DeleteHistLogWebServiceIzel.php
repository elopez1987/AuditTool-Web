<?php

namespace App\Console\Commands\General;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeleteHistLogWebServiceIzel extends Command
{
    protected $signature = 'command:deletehistlogwebserviceizel';

    protected $description = 'Borra los registros de la tabla systemeventwebserviceizel mayor a 10 dias';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $date = Carbon::today()->subDays(30);
        DB::table('systemeventwebserviceizel')->where('created_at', '<', $date)->delete();
    }
}
