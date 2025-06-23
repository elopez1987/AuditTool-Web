<?php

namespace App\Http\Controllers\Counting;

use App\Http\Controllers\BaseController;
use App\Models\Inventory\FiscalInventory\Head\InventoryHead;
use DB;

class ResetFiscalInventoryController extends BaseController
{

    public function resetInventory($id)
    {
        DB::beginTransaction();
        try {
            $inventoryHead = InventoryHead::find($id);
            $inventoryId = $inventoryHead->id;
            $this->resetInventoryCards($inventoryId);
            $this->resetInventoryLocations($inventoryId);
            $this->resetInventoryLocationScans($inventoryId, 'inventorylocationscan01');
            $this->resetInventoryLocationScans($inventoryId, 'inventorylocationscan02');
            DB::commit();
            $success = true;
        } catch (\Exception) {
            $success = false;
            DB::rollback();
        }
        return $success
            ? $this->redirectBackSuccessMessage('Inventario restaurado con exito!!')
            : $this->errorResponse('Error al reiniciar el inventario.');
    }

    private function resetInventoryCards($inventoryId)
    {
        DB::table('inventorycards')
            ->where('inventoryid', $inventoryId)
            ->update([
                'startdate01' => null,
                'finishdate01' => null,
                'responsible01' => null,
                'startdate02' => null,
                'finishdate02' => null,
                'responsible02' => null,
                'statusid' => 'PN'
            ]);
    }

    private function resetInventoryLocations($inventoryId)
    {
        DB::table('inventorylocations')
            ->where('inventoryid', $inventoryId)
            ->update([
                'statusid01' => 'PN',
                'statusid02' => 'PM',
                'statusid03' => 'PN'
            ]);
    }

    private function resetInventoryLocationScans($inventoryId, $tableName)
    {
        DB::table($tableName)
            ->where('inventoryid', $inventoryId)
            ->update([
                'partnumberid' => null,
                'quantity' => null,
                'createuser' => null
            ]);
    }

    public function resetinventorydron($id)
    {
        $inventoryHead = InventoryHead::find($id);
        DB::table('inventorylocationsdron')
            ->where('inventoryid', $inventoryHead->id)
            ->update([
                'error' => null,
                'validatedate' => null,
                'usercheck' => null
            ]);
        return $this->redirectBackSuccessMessage('Inventario restaurado con exito!!!');
    }
}
