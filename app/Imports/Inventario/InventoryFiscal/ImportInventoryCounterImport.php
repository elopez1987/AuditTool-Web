<?php

namespace App\Imports\Inventario\InventoryFiscal;

use App\Models\Inventory\InventoryFiscal\InventoryCard;
use App\Models\Inventory\InventoryFiscal\InventoryCardCount;
use App\Models\Inventory\InventoryFiscal\InventoryHead;
use App\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportInventoryCounterImport implements ShouldQueue, ToCollection, WithChunkReading, WithHeadingRow
{
    protected array $failedRows = [];

    public function __construct(
        protected InventoryHead $inventoryHead,
    ) {}

    public function collection(Collection $rows): void
    {
        if ($rows->isEmpty()) {
            return;
        }

        foreach ($rows as $row) {
            Log::info('Inicia la importacion de datos');

            $card = InventoryCard::where('name', $row['cardid'])->select('id', 'name')->first();

            if (is_null($card)) {
                $this->failedRows[] = "La Cédula no existe: {$row['cardid']}";

                continue;
            }

            Log::info('La cedula a actualizar es : ' . $card->name);

            $user = User::where('username', $row['username'])->select('id', 'name')->first();

            Log::info($user);

            Log::info('El usuario a asignar es: ' . $user->name);

            if ($row['count_number'] == '1') {
                InventoryCardCount::where('inventory_head_id', $this->inventoryHead->id)
                    ->where('inventory_card_id', $card->id)
                    ->update([
                        'user_count01_id' => $user->id,
                    ]);
            }
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
        return 400; // ahora que no hay N+1, un chunk más grande es más eficiente
    }
}
