<?php

namespace App\Console\Commands;

use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\StorageLocation;
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
    public function handle()
    {
        $receiptUnits = ReceiptUnit::controlAccessUser()
            ->whereNotIn('statusid', ['TR'])
            ->select(['id', 'zoneid', 'locationid'])
            ->get();

        foreach ($receiptUnits as $receiptUnit) {

            $locations = StorageLocation::controlAccessUser()
                ->whereIn('id', $receiptUnit->locationid)
                ->select(['id', 'zoneid'])
                ->first();

            $receiptUnit->update(['zoneid' => $locations->zoneid]);
        }
    }
}
