<?php

namespace App\Http\Controllers\Inventory\InventoryFiscal;

use App\Http\Controllers\BaseController;
use App\Models\Inventory\InventoryFiscal\InventoryLocationCount;
use App\Models\Inventory\InventoryFiscal\InventoryLocationEvidences;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
                'inventory_location_counts.statusid_count02',
                'inventory_location_counts.statusid_location',
            ])->first();
        if (is_null($locationCount)) {
            return $this->errorResponse('Location count not found');
        }

        $evidences = InventoryLocationEvidences::where('inventory_location_count_id', $id)->get();

        return view('Inventory.InventoryHead.LocationCard.ValidateLocation.index',
            compact('locationCount', 'evidences'));
    }

    public function saveResponseSuccessLocation($id)
    {
        $locationCount = InventoryLocationCount::where('id', $id)->first();

        if (is_null($locationCount)) {
            return $this->errorResponse('Location count not found');
        }

        DB::beginTransaction();

        try {
            $locationCount->update([
                'user_count02_id' => Auth::id(),
                'statusid_count02' => 'C',
                'statusid_location' => true,
            ]);

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
                ]
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
