<?php

namespace App\Http\Controllers\Inventory\InventoryFiscal;

use App\Http\Controllers\BaseController;
use App\Models\Inventory\InventoryFiscal\ImportInventoryWms;
use App\Models\Inventory\InventoryFiscal\InventoryCardCount;
use App\Models\Inventory\InventoryFiscal\InventoryLocationCount;
use App\Models\Inventory\InventoryFiscal\InventoryLocationEvidences;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InventoryValidateLocationController extends BaseController
{
    public function index($id)
    {
        $locationCount = InventoryLocationCount::where('inventory_location_counts.id', $id)
            ->with(['inventoryhead:id,name,inventory_uuid', 'inventoryaisle:id,name',
                'inventorycard:id,name', 'inventorylocation:id,name',
                'inventorypartnumber01:id,prtnum,lngdsc', 'userCount01:id,name'])
            ->leftJoin('inventory_card_counts', 'inventory_location_counts.inventory_card_id', '=', 'inventory_card_counts.inventory_card_id')
            ->select([
                'inventory_location_counts.id as id',
                'inventory_location_counts.inventory_head_id',
                'inventory_location_counts.inventory_aisle_id',
                'inventory_location_counts.inventory_card_id',
                'inventory_card_counts.id as card_id',
                'inventory_location_counts.inventory_location_id',
                'inventory_location_counts.inventorypartnumber01_id',
                'inventory_location_counts.user_count01_id',
                'inventory_location_counts.quantity01',
                'inventory_location_counts.statusid_count01',
                'inventory_location_counts.statusid_count02',
                'inventory_location_counts.statusid_location',
            ])
//            ->where('inventory_card_counts.inventory_head_id', $id)
            ->first();
        if (is_null($locationCount)) {
            return $this->errorResponse('Location count not found');
        }

        $locationStock = ImportInventoryWms::where('inventory_location_id', $locationCount->inventory_location_id)
            ->select(['id', 'inventorypartnumber_id', 'quantity_lpns', 'quantity_location'])
            ->first();

        $evidences = InventoryLocationEvidences::where('inventory_location_count_id', $id)->get();

        return view(
            'Inventory.InventoryHead.LocationCard.ValidateLocation.index',
            compact('locationCount', 'evidences', 'locationStock')
        );
    }

    public function saveResponseSuccessLocation($id)
    {
        $locationCount = InventoryLocationCount::where('id', $id)->first();
        Log::error($locationCount);

        if (is_null($locationCount)) {
            return $this->errorResponse('Location count not found');
        }

        $inventoryCardCount = InventoryCardCount::where('inventory_head_id', $locationCount->inventory_head_id)
            ->where('inventory_card_id', $locationCount->inventory_card_id)
            ->select(['id', 'user_count02_id'])
            ->first();

        Log::info($inventoryCardCount);
        if (is_null($inventoryCardCount)) {
            return $this->errorResponse('Inventory card count not found');
        }

        DB::beginTransaction();

        try {
            $locationCount->update([
                'user_count02_id' => Auth::id(),
                'statusid_count02' => 'C',
                'statusid_location' => true,
            ]);

            if ($inventoryCardCount->user_count02_id == null) {
                $inventoryCardCount->update([
                    'user_count02_id' => Auth::id(),
                ]);
            }

            DB::commit();

            return $this->responseSuccessAjax('Registro actualizado correctamente', 'saveResponseSuccessLocation');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error en saveResponseSuccessLocation', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user_id' => Auth::id(),
                'location_count_id' => $id,
                'trace' => $e->getTraceAsString(),
            ]);

            return $this->errorResponse('Error al procesar la solicitud, favor de intentarlo más tarde.');
        }
    }

    public function saveResponseErrorLocation($id)
    {
        $locationCount = InventoryLocationCount::where('id', $id)->first();
        if (is_null($locationCount)) {
            return $this->errorResponse('Location count not found');
        }
        DB::beginTransaction();

        try {
            $locationCount->update(
                [
                    'user_count02_id' => Auth::id(),
                    'statusid_count02' => 'C',
                    'statusid_location' => false,
                ],
            );

            DB::commit();

            return $this->responseSuccessAjax('Regisrto actializado correctamente', 'saveResponseErrorLocation');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error en saveResponseErrorLocation', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user_id' => Auth::id(),
                'location_count_id' => $id,
                'trace' => $e->getTraceAsString(),
            ]);

            return $this->errorResponse('Error al procesar la solicitud, favor de intentarlo más tarde.');
        }

    }
}
