<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateZoneToUnits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-zone-to-units';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualiza las zonas de las unidades en stock segun su ubicacion actual';

    /**
     * Execute the console command.
     */
    public function handle() {}
}
