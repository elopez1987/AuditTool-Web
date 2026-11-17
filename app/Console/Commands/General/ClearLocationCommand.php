<?php

namespace App\Console\Commands\General;

use App\Traits\Web\General\GetParameters;
use Illuminate\Console\Command;

class ClearLocationCommand extends Command
{
    use GetParameters;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'system:clearlocation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Realiza la limpieza de ubicaciones colgadas en el proceso de autorizacion de salida';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle() {}
}
