<?php

namespace App\Http\Controllers\Inventory\InventoryFiscal;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Inventory\InventoryFiscal\InventoryLocationRequest;
use App\Imports\Inventario\InventoryFiscal\ImportLocationInventory;
use App\Models\Inventory\InventoryFiscal\InventoryCard;
use App\Models\Inventory\InventoryFiscal\InventoryLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class InventoryLocationController extends BaseController
{
    public function createLocation(InventoryLocationRequest $request, $id)
    {
        $inventoryCard = InventoryCard::findOrFail($id);
        InventoryLocation::create([
            'inventory_card_id' => $inventoryCard->id,
            'name' => $request->input('name'),
            'inventoryunitsmeasure_id' => $request->input('inventoryunitsmeasure_id'),
        ]);
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Ubicacion creada con exito!!',
            ]);
        }

        return $this->redirectBackSuccessMessage('Ubicacion creada con exito!!');
    }

    public function getLocationForCard(Request $request)
    {
        $data = InventoryLocation::with(['inventoryunitsmeasure:id,description', 'status:statusid,name,auxiliary01'])
            ->withTrashed()
            ->where('inventory_card_id', $request->input('inventory_card_id'))
            ->get();

        return $this->showDataToView($data,
            'Inventory.InventoryLocation.partials.buttonsforcard');
    }

    public function show(InventoryLocation $inventorylocation)
    {
        $location = InventoryLocation::where('id', $inventorylocation->id)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $location->id,
                'name' => $location->name,
                'inventoryunitsmeasure_id' => $location->inventoryunitsmeasure_id,
                'auxiliary01' => $location->auxiliary01,
                'auxiliary02' => $location->auxiliary02,
                'auxiliary03' => $location->auxiliary03,
                'statusid' => $location->statusid,
            ],
        ]);
    }

    //    public function edit(InventoryLocation $inventoryLocation) {}

    public function update(InventoryLocationRequest $request, InventoryLocation $inventorylocation)
    {
        $location = InventoryLocation::where('id', $inventorylocation->id)
            ->firstOrFail();

        $location->update($request->validated());

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Ubicacion actualizada con exito!!',
            ]);
        }

        return $this->redirectBackSuccessMessage('Ubicacion actualizada con exito!!');
    }

    public function destroy($id)
    {
        try {
            $this->authorize('inventorylocations.destroy');

            $location = InventoryLocation::withTrashed()
                ->where('id', $id)
                ->firstOrFail();

            if ($location->trashed()) {
                $location->update([
                    'statusid' => 'E',
                ]);
                $location->restore();

                return response()->json([
                    'success' => true,
                    'message' => 'Ubicación restaurada con éxito!!',
                    'action' => 'restore',
                ]);
            }

            $location->update([
                'statusid' => 'D',
            ]);
            $location->delete();

            return response()->json([
                'success' => true,
                'message' => 'Ubicación eliminada con éxito!!',
                'action' => 'delete',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la ubicación: ' . $th->getMessage(),
            ], 500);
        }
    }

    public function importLocations(Request $request)
    {
        $validated = $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls', 'max:10240'],
        ]);

        try {
            Excel::import(new ImportLocationInventory, $validated['file']);
        } catch (\Throwable $e) {
            Log::error('Error al encolar import de ubicaciones de inventario', [
                'message' => $e->getMessage(),
            ]);

            return $this->errorResponse('No se pudo iniciar la importación.');
        }

        return $this->redirectBackSuccessMessage(
            'Tu archivo se está procesando en segundo plano. Te notificaremos cuando termine.',
        );

    }
}
