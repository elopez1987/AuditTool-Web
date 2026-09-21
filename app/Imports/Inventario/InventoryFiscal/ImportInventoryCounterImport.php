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

        // 1. Cargar todas las Cédulas y Usuarios necesarios en 2 queries (no N+1)
        $cardIds = $rows->pluck('cardid')->unique()->filter();
        $usernames = $rows->pluck('username')->unique()->filter();

        $cards = InventoryCard::whereIn('name', $cardIds)
            ->select('id', 'name')
            ->get()
            ->keyBy('name');

        $users = User::whereIn('username', $usernames)
            ->select('id', 'name', 'username')
            ->get()
            ->keyBy('username');

        // 2. Preparar los updates in memoria, agrupados for count_number
        $updatesByCount = [
            '1' => [],
            '2' => [],
        ];

        foreach ($rows as $row) {
            $card = $cards->get($row['cardid']);

            if (is_null($card)) {
                $this->failedRows[] = "La Cédula no existe: {$row['cardid']}";

                continue;
            }

            $user = $users->get($row['username']);

            if (is_null($user)) {
                $this->failedRows[] = "El usuario no existe: {$row['username']} (Cédula: {$row['cardid']})";

                continue;
            }

            if (isset($updatesByCount[$row['count_number']])) {
                $updatesByCount[$row['count_number']][$card->id] = $user->id;
            }
        }

        // 3. Ejecutar los updates ya agrupados (1 query por grupo de count_number, vía case/when)
        $this->applyBulkUpdate($updatesByCount['1'], 'user_count01_id');
        $this->applyBulkUpdate($updatesByCount['2'], 'user_count02_id');

        if (! empty($this->failedRows)) {
            Log::warning('Filas omitidas en import de inventario', [
                'inventory_head_id' => $this->inventoryHead->id,
                'total_omitidas' => count($this->failedRows),
                'detalle' => $this->failedRows,
            ]);
        }
    }

    private function applyBulkUpdate(array $cardIdToUserId, string $column): void
    {
        foreach ($cardIdToUserId as $cardId => $userId) {
            InventoryCardCount::where('inventory_head_id', $this->inventoryHead->id)
                ->where('inventory_card_id', $cardId)
                ->update([$column => $userId]);
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
