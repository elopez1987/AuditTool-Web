<?php

namespace App\Http\Controllers\Yard\ReceiptUnit\Inventory;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Yard\Exception;
use App\Http\Requests\Web\Yard\Inventory\CreateMovUnitForInventoryRequest;
use App\Models\Configuration\Parameters\SystemDetailParameter;
use App\Models\Yard\InventoryBox;
use App\Models\Yard\InventoryCountStock;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\StorageLocation;
use App\Models\Yard\StorageUnitTrace;
use App\Models\Yard\StorageZone\StorageZone;
use DB;

class InventoryCountStocksController extends BaseController
{
    public function index()
    {
        $this->authorize('inventoryboxcount.index');
        $zones = StorageZone::controlAccessUser()
            ->whereNotIn('zoneid', ['ST'])->select(['name', 'id'])->get();
        $locations = StorageLocation::controlAccessUser()->select(['name', 'id'])->get();
        $unitstatus = $this->unitStatusActiveToSelect();

        return view('Yards.InventoryBoxesCount.index', compact('zones', 'locations', 'unitstatus'));
    }

    public function store(CreateMovUnitForInventoryRequest $request)
    {
        $error = null;
        DB::beginTransaction();
        try {
            $receiptunit = ReceiptUnit::searchForId($request['folionumber']);
            if (empty($receiptunit)) {
                return $this->errorResponse('El número de folio no existe!!');
            }
            $invAct = InventoryBox::controlAccessUser()->where('statusid', '=', 'AT')->first(['id']);
            if (empty($invAct)) {
                return $this->errorResponse('No existe inventario activo,favor de validar!!');
            }
            $inventoryBox = InventoryCountStock::searchForFolioNumber($receiptunit->id, $invAct);
            if (empty($inventoryBox)) {
                return $this->errorResponse('No existe registro en el inventario creado');
            }
            if ($request['locationid'] !== $receiptunit->locationid) {
                $receiptunitAct = ReceiptUnit::searchUnitForLocation($request['locationid']);
                if (! empty($receiptunitAct)) {
                    $locationlost = SystemDetailParameter::getDetailParameter('UBI-LOST', $receiptunit->centerid);
                    if (empty($locationlost)) {
                        return $this->errorResponse('No existe ubicación LOST parametrizada, favor de validar el parametro UBI-LOST.!!');
                    }
                    ReceiptUnit::moveUnitToLostForInventory($receiptunitAct, $locationlost);
                    StorageUnitTrace::createTraceUnit($receiptunitAct, '24');
                }
                StorageLocation::updateLocation($receiptunit->locationid, 'E', '');
                ReceiptUnit::updateUnitForInventory($receiptunit, $request);
            }
            StorageLocation::updateLocation($request['locationid'], 'D', $receiptunit->boxnumber);
            InventoryCountStock::updateInventory($inventoryBox, $request);
            StorageUnitTrace::createTraceUnit($receiptunit, '24');

            DB::commit();
            $success = true;
        } catch (Exception $e) {
            $success = false;
            $error = $e->getMessage();
            DB::rollback();
        }
        if ($success) {
            return $this->redirectBackSuccessMessage('Caja reubicada correctamente!!');
        }

        return $this->errorResponse('Error el procesar la solicitud' . $error);
    }
}
