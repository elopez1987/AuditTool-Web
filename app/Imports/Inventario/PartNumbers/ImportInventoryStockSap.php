<?php

declare(strict_types=1);

namespace App\Imports\Inventario\PartNumbers;

use App\Models\Inventory\InventoryFiscal\InventoryHead;
use App\Models\Inventory\PartNumber\InventoryPartnumber;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportInventoryStockSap implements ShouldQueue, ToCollection, WithChunkReading, WithHeadingRow
{
    /** Filas que no se pudieron procesar, para reportar al final. */
    protected array $failedRows = [];

    public function __construct(
        protected readonly InventoryHead $inventoryHead,
    ) {}

    /**
     * Se ejecuta una vez POR CHUNK (no por fila), gracias a ToCollection.
     * El archivo de SAP solo trae prtnum y quantity.
     */
    public function collection(Collection $rows): void
    {
        if ($rows->isEmpty()) {
            return;
        }

        $partNumbers = $rows->pluck('prtnum')
            ->filter()
            ->map(fn ($value) => (string) $value)
            ->unique()
            ->values();

        $partNumberRecords = InventoryPartnumber::query()
            ->where('centerid', $this->inventoryHead->centerid)
            ->where('warehouseid', $this->inventoryHead->warehouseid)
            ->whereIn('prtnum', $partNumbers)
            ->pluck('id', 'prtnum');

        $upsertData = [];

        foreach ($rows as $row) {
            $partNumberId = $partNumberRecords->get($row['prtnum']);
            if (! $partNumberId) {
                $this->failedRows[] = "Número de parte no existe: {$row['prtnum']}";

                continue;
            }

            $upsertData[] = [
                'inventory_head_id' => $this->inventoryHead->id,
                'inventorypartnumber_id' => $partNumberId,
                'quantity' => $row['quantity'],
            ];
        }

        // --- 4. UN solo upsert masivo (crear o actualizar) para todo el chunk ---
        if (! empty($upsertData)) {
            DB::transaction(function () use ($upsertData) {
                DB::table('inventory_stock_sap')->upsert(
                    $upsertData,
                    ['inventory_head_id', 'inventorypartnumber_id'], // columnas únicas (requiere índice único compuesto)
                    ['quantity'],
                );
            });
        }

        if (! empty($this->failedRows)) {
            Log::warning('Filas omitidas en import de inventario SAP', [
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
        return 1000; // sin N+1, un chunk más grande es más eficiente
    }
}
