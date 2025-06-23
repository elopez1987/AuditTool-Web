<?php

namespace App\Http\Controllers\Services\Yard;

use App\Http\Controllers\Controller;
use App\Models\Configuration\Parameters\SystemDetailParameter;
use App\Models\Yard\InventoryBox;
use App\Models\Yard\InventoryCountStock;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\StorageLocation;
use App\Models\Yard\StorageUnitTrace;
use App\Traits\ResponseApi;
use DB;
use Illuminate\Http\Request;

class InventoryCountController extends Controller
{
    use ResponseApi;

    public function saveInventoryCountUnit(Request $request)
    {
        if ($request['folionumber'] == null || $request['unitstatusid'] == null || $request['locationid'] == null || $request['zoneid'] == null) {
            return $this->getResponseWithErrors('saveInventoryCountUnit', 'El folio,estatus,zona y ubicacion son valores obligatorios.');
        }
        DB::beginTransaction();
        try {
            $receiptunit = ReceiptUnit::searchForId($request['folionumber']);
            if (empty($receiptunit)) {
                return $this->getResponseWithErrors('saveInventoryCountUnit', 'El número de folio no existe.');
            }
            $invAct = InventoryBox::controlAccessUser()->where('statusid', '=', 'PN')->first(['id']);
            if (empty($invAct)) {
                return $this->getResponseWithErrors('saveInventoryCountUnit', 'No existe inventario activo, favor de validar.');
            }
            $inventoryBox = InventoryCountStock::searchForFolioNumber($receiptunit->id, $invAct);
            if (empty($inventoryBox)) {
                return $this->getResponseWithErrors('saveInventoryCountUnit', 'No existe registro en el inventario creado.');
            }
            if ($request['locationid'] != $receiptunit->locationid) {
                $receiptunitAct = ReceiptUnit::searchUnitForLocation($request['locationid']);
                if (!empty($receiptunitAct)) {
                    $locationlost = SystemDetailParameter::getDetailParameter('UBI-LOST', $receiptunit->centerid);
                    if (empty($locationlost)) {
                        return $this->getResponseWithErrors('saveInventoryCountUnit', 'No existe ubicación LOST parametrizada,favor de validar el parametro UBI-LOST.');
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
            return $this->getResponseSuccess('saveInventoryCountUnit', $receiptunit);
        } else {
            return $this->getResponseWithErrors('saveInventoryCountUnit', 'Error al procesar la solicitud.');
        }
    }


}
