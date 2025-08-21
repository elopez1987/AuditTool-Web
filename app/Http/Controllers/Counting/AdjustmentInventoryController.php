<?php

namespace App\Http\Controllers\Counting;

use App\Http\Controllers\BaseController;
use App\Http\Services\Counting\ServiceAdjustmentInventory;
use App\Models\Inventory\FiscalInventory\Cards\InventoryCard;
use App\Models\Inventory\FiscalInventory\Dron\InventoryLocationForDron;
use App\Models\Inventory\FiscalInventory\Head\InventoryHead;
use App\Traits\Web\Counting\QueryAdjustmentInventory;
use DB;
use Exception;
use Illuminate\Http\Request;

class AdjustmentInventoryController extends BaseController
{
    use QueryAdjustmentInventory;

    private ServiceAdjustmentInventory $serviceAdjustmentInventory;

    public function __construct(ServiceAdjustmentInventory $serviceAdjustmentInventory)
    {
        $this->serviceAdjustmentInventory = $serviceAdjustmentInventory;
    }

    public function adjustmentinventory($id)
    {
        $inventory = InventoryHead::findOrFail($id);
        return view('Counting.InventoryHead.Adjustment.index',
            compact('inventory'));
    }

    public function getCardWithErrors(Request $request)
    {
        $inventoryHead = InventoryHead::where('id', $request['inventoryid'])->first();
        if ($inventoryHead->inventorytype == 'APP') {
            $data = $this->getAllLocationsErrors($request);
        }
        if ($inventoryHead->inventorytype == 'DRON') {
            $data = $this->getLocationWithErrorForCountDron($request);
        }
        return $this->showDataToView($data,
            'Counting.InventoryHead.Adjustment.btn');
    }

    public function adjustmentcard($id)
    {
        $card = InventoryCard::findOrFail($id);
        $inventory = InventoryHead::findOrFail($card->inventoryid);
        if ($inventory->inventorytype == 'APP') {
            $locations = $this->getLocationsForAdjustCard($card, $inventory);
            return view('Counting.InventoryHead.Adjustment.adjustmentcount',
                compact('card', 'locations', 'inventory'));
        }

        if ($inventory->inventorytype == 'DRON') {
            $locations = InventoryLocationForDron::where('inventoryid', $inventory->id)
                ->where('error', '=', 'S')->where('cardid', $card->cardid)->get();
            return view('Counting.InventoryHeadDron.Adjustment.adjustmentcount',
                compact('card', 'locations', 'inventory'));
        }
    }

    public function storeajustmentcountdron(Request $request)
    {
        $error = null;
        DB::beginTransaction();
        try {

            $datos = $request['locationid'];
            $registros = count($datos) - 1;
            for ($i = 0; $i <= $registros; $i++) {
                $locationCount = InventoryLocationForDron::where('inventoryid', $request['inventoryid'])
                    ->where('locationid', $request['locationid'][$i])->first();
                $locationCount->partnumberid = $request['partnumbernew'][$i];
                $locationCount->quantity = $request['quantitynew'][$i];
                $locationCount->error = 'N';
                $locationCount->save();
                $this->serviceAdjustmentInventory->saveAdjustmentInventoryForDron($locationCount, $request['partnumbernew'][$i], $request['quantitynew'][$i]);
            }

            DB::commit();
            $success = true;
        } catch (Exception $e) {
            $success = false;
            $error = $e->getMessage();
            DB::rollback();
        }
        if ($success) {
            return redirect('adjustmentinventory/' . $request['inventoryid']);
        } else {
            return $this->errorResponse('Error al procesar la solicitud, intente nuevamente' . $error);
        }
    }


    public function storeajustmentcount(Request $request)
    {
        $error = null;
        DB::beginTransaction();
        try {
            $datos = $request['locationid'];
            $registros = count($datos) - 1;
            for ($i = 0; $i <= $registros; $i++) {
                $count01 = $this->getLocationCount01($request, $i);
                if (!empty($count01)) {
                    $originalCount = $this->serviceAdjustmentInventory->adjustmentcount01($count01);
                    if ($count01->partnumberid != $request['partnumber01'][$i]) {
                        $count01->partnumberid = $request['partnumber01'][$i];
                        $count01->save();
                    }
                    if ($count01->quantity != $request['quantity01'][$i]) {
                        $count01->quantity = $request['quantity01'][$i];
                        $count01->save();
                    }
                }
                $count02 = $this->getLocationCount02($request, $i);
                if (!empty($count02)) {
                    $this->serviceAdjustmentInventory->updateOriginalCount($count02, $originalCount);
                    if ($count02->partnumberid != $request['partnumber02'][$i]) {
                        $count02->partnumberid = $request['partnumber02'][$i];
                        $count02->save();
                    }
                    if ($count02->quantity != $request['quantity02'][$i]) {
                        $count02->quantity = $request['quantity02'][$i];
                        $count02->save();
                    }
                }
                $this->serviceAdjustmentInventory->saveAdjustmentInventory($count01, $count02);
            }
            DB::commit();
            $success = true;
        } catch (Exception $e) {
            $success = false;
            $error = $e->getMessage();
            DB::rollback();
        }
        if ($success) {
            return redirect('adjustmentinventory/' . $request['inventoryid']);
        } else {
            return $this->errorResponse('Error al procesar la solicitud, intente nuevamente' . $error);
        }
    }


}
