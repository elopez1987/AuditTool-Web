<?php

namespace App\Services\Inventory;

use App\Exceptions\BusinessException;
use App\Imports\Inventario\InventoryFiscal\ImportInventoryCounterImport;
use App\Models\Inventory\InventoryFiscal\InventoryHead;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class InventoryCounterImportService
{
    /**
     * Encola la importación masiva de contadores de cédulas para un inventario.
     */
    public function import(UploadedFile $file, int $inventoryHeadId): void
    {
        $inventoryHead = $this->findAccessibleInventoryHead($inventoryHeadId);

        try {
            Excel::import(new ImportInventoryCounterImport($inventoryHead), $file);
        } catch (Throwable $e) {
            Log::error('Error al encolar import de inventario', [
                'inventory_head_id' => $inventoryHead->id,
                'message' => $e->getMessage(),
            ]);

            throw new BusinessException(
                'No se pudo iniciar la importación.',
                previous: $e,
            );
        }
    }

    private function findAccessibleInventoryHead(int $inventoryHeadId): InventoryHead
    {
        $inventoryHead = InventoryHead::controlAccessUser()
            ->where('id', $inventoryHeadId)
            ->first();

        if (is_null($inventoryHead)) {
            throw new BusinessException('Inventory head not found', code: 404);
        }

        return $inventoryHead;
    }
}
