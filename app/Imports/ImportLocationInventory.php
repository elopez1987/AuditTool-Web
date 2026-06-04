<?php

namespace App\Imports;

use App\Models\Inventory\InventoryFiscal\InventoryAisle;
use App\Models\Inventory\InventoryFiscal\InventoryArea;
use App\Models\Inventory\InventoryFiscal\InventoryCard;
use App\Models\Inventory\InventoryFiscal\InventoryLocation;
use App\Models\Inventory\UnitMeasure\InventoryUnitMeasure;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ImportLocationInventory implements ToCollection, WithHeadingRow, WithValidation
{
    public function collection(Collection $rows): void
    {
        // --- Pre-carga de catálogos fijos (2 queries) ---
        $areaNames = $rows->pluck('areaid')->map(fn ($v) => trim($v))->unique();
        $unitMeasureKeys = $rows->pluck('unitmeasureid')->map(fn ($v) => trim($v))->unique();

        $areas = InventoryArea::whereIn('name', $areaNames)
            ->get()
            ->keyBy(fn ($a) => trim($a->name));

        $unitMeasures = InventoryUnitMeasure::whereIn('unitmeasureid', $unitMeasureKeys)
            ->get()
            ->keyBy(fn ($u) => trim($u->unitmeasureid));

        // --- Cachés en memoria para evitar N+1 en pasillos y cédulas ---
        $aisleCache = collect();
        $cardCache = collect();

        foreach ($rows as $index => $row) {
            $areaName = trim($row['areaid']);
            $unitMeasureKey = trim($row['unitmeasureid']);
            $aisleName = trim($row['aisleid']);
            $cardName = trim($row['cardid']);
            $locationName = trim($row['locationid']);

            $area = $areas->get($areaName);
            $unitMeasure = $unitMeasures->get($unitMeasureKey);

            if (is_null($area) || is_null($unitMeasure)) {
                Log::warning('ImportLocationInventory: fila omitida por catálogo no encontrado', [
                    'fila' => $index + 2,
                    'areaid' => $areaName,
                    'unitmeasureid' => $unitMeasureKey,
                ]);

                continue;
            }

            // Pasillo — consulta solo si no está en caché
            $aisleKey = "{$area->centerid}|{$area->warehouseid}|{$aisleName}";
            $aisle = $aisleCache->get($aisleKey);

            if (is_null($aisle)) {
                $aisle = InventoryAisle::updateOrCreate([
                    'centerid' => $area->centerid,
                    'warehouseid' => $area->warehouseid,
                    'name' => $aisleName,
                ]);
                $aisleCache->put($aisleKey, $aisle);
            }

            // Cédula — consulta solo si no está en caché
            $cardKey = "{$area->centerid}|{$area->warehouseid}|{$aisle->id}|{$area->id}|{$cardName}";
            $card = $cardCache->get($cardKey);

            if (is_null($card)) {
                $card = InventoryCard::updateOrCreate([
                    'inventory_aisle_id' => $aisle->id,
                    'inventory_area_id' => $area->id,
                    'name' => $cardName,
                ]);
                $cardCache->put($cardKey, $card);
            }

            // Ubicación
            InventoryLocation::updateOrCreate(
                [
                    'inventory_card_id' => $card->id,
                    'name' => $locationName,
                ],
                [
                    'inventoryunitsmeasure_id' => $unitMeasure->id,
                ]
            );
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
}
