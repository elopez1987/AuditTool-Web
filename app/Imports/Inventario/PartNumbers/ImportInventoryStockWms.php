<?php

declare(strict_types=1);

namespace App\Imports\Inventario\PartNumbers;

use App\Models\Inventory\InventoryFiscal\InventoryHead;
use App\Models\Inventory\InventoryFiscal\InventoryLocation;
use App\Models\Inventory\PartNumber\InventoryPartnumber;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportInventoryStockWms implements ShouldQueue, ToCollection, WithChunkReading, WithHeadingRow
{
    /** Filas que no se pudieron procesar, para reportar al final. */
    protected array $failedRows = [];

    public function __construct(
        protected readonly InventoryHead $inventoryHead
    ) {}

    /**
     * Se ejecuta una vez POR CHUNK (no por fila), gracias a ToCollection.
     */
    public function collection(Collection $rows): void
    {
        if ($rows->isEmpty()) {
            return;
        }

        // --- 1. Recolectar los identificadores únicos que necesita este chunk ---
        $locationNames = $rows->pluck('location_name')->filter()->map(fn ($value) => (string) $value)->unique()->values();
        $partNumbers = $rows->pluck('prtnum')->filter()->map(fn ($value) => (string) $value)->unique()->values();

        // --- 2. UNA sola query por lookup, para TODO el chunk (en vez de 1 por fila) ---
        $locations = InventoryLocation::whereIn('name', $locationNames)
            ->pluck('id', 'name');

        $partNumberRecords = InventoryPartnumber::where('centerid', $this->inventoryHead->centerid)
            ->where('warehouseid', $this->inventoryHead->warehouseid)
            ->whereIn('prtnum', $partNumbers)
            ->pluck('id', 'prtnum');

        // --- 3. Armar el array de upsert en memoria (sin tocar la BD todavía) ---
        $upsertData = [];

        foreach ($rows as $row) {
            $locationId = $locations->get($row['location_name']);
            if (! $locationId) {
                $this->failedRows[] = "Ubicación no existe: {$row['location_name']}";

                continue;
            }

            $partNumberId = $partNumberRecords->get($row['prtnum']);
            if (! $partNumberId) {
                $this->failedRows[] = "Número de parte no existe: {$row['prtnum']}";

                continue;
            }

            $upsertData[] = [
                'inventory_head_id' => $this->inventoryHead->id,
                'inventory_location_id' => $locationId,
                'inventorypartnumber_id' => $partNumberId,
                'quantity_location' => $row['quantity_location'],
                'quantity_lpns' => $row['quantity_lpn'],
            ];

        }

        // --- 4. UN solo upsert masivo para todo el chunk, dentro de transacción ---
        if (! empty($upsertData)) {
            DB::transaction(function () use ($upsertData) {
                DB::table('inventory_stock_wms')->upsert(
                    $upsertData,
                    ['inventory_head_id', 'inventory_location_id'], // columnas únicas (requiere índice único compuesto)
                    ['inventorypartnumber_id', 'quantity_location', 'quantity_lpns']
                );
            });
        }

        if (! empty($this->failedRows)) {
            Log::warning('Filas omitidas en import de inventario', [
                'inventory_head_id' => $this->inventoryHead->id,
                'total_omitidas' => count($this->failedRows),
                'detalle' => $this->failedRows,
            ]);
        }
    }

    public function headingRow(): int
    {
        return 1;
    }

    public function chunkSize(): int
    {
        return 1000; // ahora que no hay N+1, un chunk más grande es más eficiente
    }
}
