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
        $this->info('Iniciando actualización de zonas en unidades...');

        $receiptUnits = ReceiptUnit::whereNotIn('statusid', ['TR'])
            ->select(['id', 'zoneid', 'locationid'])
            ->get();

        $total = $receiptUnits->count();
        $updated = 0;
        $skipped = 0;

        $this->info("Total de unidades a procesar: {$total}");

        foreach ($receiptUnits as $receiptUnit) {

            $location = StorageLocation::where('id', $receiptUnit->locationid)
                ->select(['id', 'zoneid'])
                ->first();

            if (! $location) {
                $this->warn("Unidad ID {$receiptUnit->id}: ubicación ID {$receiptUnit->locationid} no encontrada. Se omite.");
                $skipped++;

                continue;
            }

            $this->line("Unidad ID {$receiptUnit->id}: ubicación ID {$location->id} → zona '{$location->zoneid}'");

            $receiptUnit->update(['zoneid' => $location->zoneid]);
            $updated++;
        }

        $this->info("Proceso finalizado. Actualizadas: {$updated} | Omitidas: {$skipped} | Total: {$total}");
    }
}
