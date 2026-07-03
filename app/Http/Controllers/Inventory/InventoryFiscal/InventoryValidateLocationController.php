<?php

namespace App\Http\Controllers\Inventory\InventoryFiscal;

use App\Http\Controllers\BaseController;
use App\Models\Inventory\InventoryFiscal\InventoryLocationCount;
use App\Models\Inventory\InventoryFiscal\InventoryLocationEvidences;

class InventoryValidateLocationController extends BaseController
{
    public function index($id)
    {
        $locationCount = InventoryLocationCount::where('id', $id)
            ->with(['inventoryhead:id,name,inventory_uuid', 'inventoryaisle:id,name',
                'inventorycard:id,name', 'inventorylocation:id,name',
                'inventorypartnumber01:id,prtnum,lngdsc', 'userCount01:id,name'])
            ->select([
                'inventory_location_counts.id',
                'inventory_location_counts.inventory_head_id',
                'inventory_location_counts.inventory_aisle_id',
                'inventory_location_counts.inventory_card_id',
                'inventory_location_counts.inventory_location_id',
                'inventory_location_counts.inventorypartnumber01_id',
                'inventory_location_counts.user_count01_id',
                'inventory_location_counts.quantity01',
            ])->first();
        if (is_null($locationCount)) {
            return $this->errorResponse('Location count not found');
        }

        $evidences = InventoryLocationEvidences::where('inventory_location_count_id', $id)->get();

        return view('Inventory.InventoryHead.LocationCard.ValidateLocation.index',
            compact('locationCount', 'evidences'));
    }
}
