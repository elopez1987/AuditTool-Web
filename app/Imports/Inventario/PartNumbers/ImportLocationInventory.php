<?php

declare(strict_types=1);

namespace App\Imports\Inventario\PartNumbers;

use App\Models\Inventory\InventoryFiscal\InventoryAisle;
use App\Models\Inventory\InventoryFiscal\InventoryArea;
use App\Models\Inventory\InventoryFiscal\InventoryCard;
use App\Models\Inventory\InventoryFiscal\InventoryLocation;
use App\Models\Inventory\UnitMeasure\InventoryUnitMeasure;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ImportLocationInventory implements ShouldQueue, ToCollection, WithChunkReading, WithHeadingRow, WithValidation
{
    /** Filas que no se pudieron procesar, para reportar al final. */
    protected array $failedRows = [];

    /**
     * Se ejecuta una vez POR CHUNK (no por fila), gracias a ToCollection.
     */
    public function collection(Collection $rows): void
    {
        if ($rows->isEmpty()) {
            return;
        }

        $preparedRows = $this->prepareRows($rows);

        if ($preparedRows->isEmpty()) {
            $this->logFailedRows();

            return;
        }

        $aisleIds = $this->resolveAisles($preparedRows);

        $preparedRows = $preparedRows->map(function (array $row) use ($aisleIds) {
            $row['inventory_aisle_id'] = $aisleIds->get("{$row['centerid']}|{$row['warehouseid']}|{$row['aisle_name']}");

            return $row;
        })->filter(function (array $row) {
            if (is_null($row['inventory_aisle_id'])) {
                $this->failedRows[] = "No se pudo resolver el pasillo: {$row['aisle_name']}";

                return false;
            }

            return true;
        })->values();

        if ($preparedRows->isEmpty()) {
            $this->logFailedRows();

            return;
        }

        $cardIds = $this->resolveCards($preparedRows);

        $preparedRows = $preparedRows->map(function (array $row) use ($cardIds) {
            $row['inventory_card_id'] = $cardIds->get("{$row['inventory_aisle_id']}|{$row['area_id']}|{$row['card_name']}");

            return $row;
        })->filter(function (array $row) {
            if (is_null($row['inventory_card_id'])) {
                $this->failedRows[] = "No se pudo resolver la cédula: {$row['card_name']}";

                return false;
            }

            return true;
        })->values();

        $this->syncLocations($preparedRows);

        $this->logFailedRows();
    }

    /**
     * Resuelve área/unidad de medida por fila con una sola query por catálogo
     * (en vez de una query por fila) y descarta las filas sin catálogo válido.
     */
    protected function prepareRows(Collection $rows): Collection
    {
        $areaNames = $rows->pluck('areaid')->map(fn ($v) => trim((string) $v))->filter()->unique()->values();
        $unitMeasureKeys = $rows->pluck('unitmeasureid')->map(fn ($v) => trim((string) $v))->filter()->unique()->values();

        $areas = InventoryArea::whereIn('name', $areaNames)
            ->get()
            ->keyBy(fn ($area) => trim($area->name));

        $unitMeasures = InventoryUnitMeasure::whereIn('unitmeasureid', $unitMeasureKeys)
            ->get()
            ->keyBy(fn ($unitMeasure) => trim($unitMeasure->unitmeasureid));

        $prepared = collect();

        foreach ($rows as $index => $row) {
            $areaName = trim((string) $row['areaid']);
            $unitMeasureKey = trim((string) $row['unitmeasureid']);

            $area = $areas->get($areaName);
            $unitMeasure = $unitMeasures->get($unitMeasureKey);

            if (is_null($area) || is_null($unitMeasure)) {
                $this->failedRows[] = "Fila {$index}: catálogo no encontrado (areaid: {$areaName}, unitmeasureid: {$unitMeasureKey})";

                continue;
            }

            $prepared->push([
                'centerid' => $area->centerid,
                'warehouseid' => $area->warehouseid,
                'area_id' => $area->id,
                'aisle_name' => trim((string) $row['aisleid']),
                'card_name' => trim((string) $row['cardid']),
                'location_name' => trim((string) $row['locationid']),
                'inventoryunitsmeasure_id' => $unitMeasure->id,
            ]);
        }

        return $prepared;
    }

    /**
     * Resuelve (y crea si faltan) los pasillos necesarios para todo el chunk
     * con una sola query de lectura y un solo insert masivo, en vez de un
     * updateOrCreate por fila.
     *
     * @return Collection<string,int> id indexado por "centerid|warehouseid|name"
     */
    protected function resolveAisles(Collection $preparedRows): Collection
    {
        $keys = $preparedRows->map(fn (array $row) => [
            'centerid' => $row['centerid'],
            'warehouseid' => $row['warehouseid'],
            'name' => $row['aisle_name'],
        ])->unique(fn (array $k) => "{$k['centerid']}|{$k['warehouseid']}|{$k['name']}")->values();

        $existing = InventoryAisle::whereIn('centerid', $keys->pluck('centerid')->unique())
            ->whereIn('warehouseid', $keys->pluck('warehouseid')->unique())
            ->whereIn('name', $keys->pluck('name')->unique())
            ->get()
            ->keyBy(fn ($aisle) => "{$aisle->centerid}|{$aisle->warehouseid}|{$aisle->name}");

        $missing = $keys->reject(
            fn (array $k) => $existing->has("{$k['centerid']}|{$k['warehouseid']}|{$k['name']}")
        )->values();

        if ($missing->isNotEmpty()) {
            $now = now();

            DB::table('inventory_aisles')->insert(
                $missing->map(fn (array $k) => [
                    'centerid' => $k['centerid'],
                    'warehouseid' => $k['warehouseid'],
                    'name' => $k['name'],
                    'statusid' => 'E',
                    'schemaid' => 'STS',
                    'created_at' => $now,
                    'updated_at' => $now,
                ])->all()
            );

            $newlyCreated = InventoryAisle::whereIn('centerid', $missing->pluck('centerid')->unique())
                ->whereIn('warehouseid', $missing->pluck('warehouseid')->unique())
                ->whereIn('name', $missing->pluck('name')->unique())
                ->get()
                ->keyBy(fn ($aisle) => "{$aisle->centerid}|{$aisle->warehouseid}|{$aisle->name}");

            $existing = $existing->merge($newlyCreated);
        }

        return $existing->map(fn ($aisle) => $aisle->id);
    }

    /**
     * Resuelve (y crea si faltan) las cédulas necesarias para todo el chunk
     * con una sola query de lectura y un solo insert masivo.
     *
     * @return Collection<string,int> id indexado por "aisle_id|area_id|name"
     */
    protected function resolveCards(Collection $preparedRows): Collection
    {
        $keys = $preparedRows->map(fn (array $row) => [
            'inventory_aisle_id' => $row['inventory_aisle_id'],
            'inventory_area_id' => $row['area_id'],
            'name' => $row['card_name'],
        ])->unique(
            fn (array $k) => "{$k['inventory_aisle_id']}|{$k['inventory_area_id']}|{$k['name']}"
        )->values();

        $existing = InventoryCard::whereIn('inventory_aisle_id', $keys->pluck('inventory_aisle_id')->unique())
            ->whereIn('inventory_area_id', $keys->pluck('inventory_area_id')->unique())
            ->whereIn('name', $keys->pluck('name')->unique())
            ->get()
            ->keyBy(fn ($card) => "{$card->inventory_aisle_id}|{$card->inventory_area_id}|{$card->name}");

        $missing = $keys->reject(
            fn (array $k) => $existing->has("{$k['inventory_aisle_id']}|{$k['inventory_area_id']}|{$k['name']}")
        )->values();

        if ($missing->isNotEmpty()) {
            $now = now();

            DB::table('inventory_cards')->insert(
                $missing->map(fn (array $k) => [
                    'inventory_aisle_id' => $k['inventory_aisle_id'],
                    'inventory_area_id' => $k['inventory_area_id'],
                    'name' => $k['name'],
                    'statusid' => 'E',
                    'schemaid' => 'STS',
                    'created_at' => $now,
                    'updated_at' => $now,
                ])->all()
            );

            $newlyCreated = InventoryCard::whereIn('inventory_aisle_id', $missing->pluck('inventory_aisle_id')->unique())
                ->whereIn('inventory_area_id', $missing->pluck('inventory_area_id')->unique())
                ->whereIn('name', $missing->pluck('name')->unique())
                ->get()
                ->keyBy(fn ($card) => "{$card->inventory_aisle_id}|{$card->inventory_area_id}|{$card->name}");

            $existing = $existing->merge($newlyCreated);
        }

        return $existing->map(fn ($card) => $card->id);
    }

    /**
     * Crea las ubicaciones nuevas con un solo insert masivo y actualiza solo
     * las que realmente cambiaron de unidad de medida, todo dentro de una
     * transacción por chunk (igual que ImportInventoryStockWms).
     */
    protected function syncLocations(Collection $preparedRows): void
    {
        if ($preparedRows->isEmpty()) {
            return;
        }

        $existing = InventoryLocation::whereIn('inventory_card_id', $preparedRows->pluck('inventory_card_id')->unique())
            ->whereIn('name', $preparedRows->pluck('location_name')->unique())
            ->get()
            ->keyBy(fn ($location) => "{$location->inventory_card_id}|{$location->name}");

        $toInsert = [];
        $toUpdate = [];
        $now = now();

        foreach ($preparedRows as $row) {
            $key = "{$row['inventory_card_id']}|{$row['location_name']}";
            $existingLocation = $existing->get($key);

            if (is_null($existingLocation)) {
                $toInsert[$key] = [
                    'inventory_card_id' => $row['inventory_card_id'],
                    'name' => $row['location_name'],
                    'inventoryunitsmeasure_id' => $row['inventoryunitsmeasure_id'],
                    'statusid' => 'E',
                    'schemaid' => 'STS',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                continue;
            }

            if ((int) $existingLocation->inventoryunitsmeasure_id !== (int) $row['inventoryunitsmeasure_id']) {
                $toUpdate[$existingLocation->id] = (int) $row['inventoryunitsmeasure_id'];
            }
        }

        if (empty($toInsert) && empty($toUpdate)) {
            return;
        }

        DB::transaction(function () use ($toInsert, $toUpdate, $now) {
            if (! empty($toInsert)) {
                DB::table('inventory_locations')->insert(array_values($toInsert));
            }

            foreach ($toUpdate as $locationId => $inventoryUnitsMeasureId) {
                DB::table('inventory_locations')
                    ->where('id', $locationId)
                    ->update([
                        'inventoryunitsmeasure_id' => $inventoryUnitsMeasureId,
                        'updated_at' => $now,
                    ]);
            }
        });
    }

    protected function logFailedRows(): void
    {
        if (! empty($this->failedRows)) {
            Log::warning('Filas omitidas en import de ubicaciones de inventario', [
                'total_omitidas' => count($this->failedRows),
                'detalle' => $this->failedRows,
            ]);

            $this->failedRows = [];
        }
    }

    public function rules(): array
    {
        return [
            '*.areaid' => ['required', 'exists:inventory_areas,name'],
            '*.unitmeasureid' => ['required', 'exists:inventoryunitsmeasure,unitmeasureid'],
            '*.locationid' => ['required'],
            '*.aisleid' => ['required', 'max:100'],
            '*.cardid' => ['required', 'max:100'],
        ];
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
