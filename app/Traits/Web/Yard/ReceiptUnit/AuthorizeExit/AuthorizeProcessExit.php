<?php

namespace App\Traits\Web\Yard\ReceiptUnit\AuthorizeExit;

use App\Models\General\StorageComment;
use App\Models\Receipt\ReceiptAsn;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Yard\CheckListTransport;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\ReceiptUnitKey;
use App\Models\Yard\StorageLocation;
use App\Models\Yard\StorageUnitTrace;
use App\Traits\ResponseApi;
use App\Traits\Web\General\GetParameters;
use App\Traits\Web\General\Responser;
use App\Traits\Web\Yard\CheckList\CheckListTransportTrait;
use Auth;
use Illuminate\Http\Request;

trait AuthorizeProcessExit
{
    use CheckListTransportTrait;
    use GetParameters;
    use ResponseApi;
    use Responser;

    public function authorizeProcess($receiptunit, $request)
    {
        $parameter = $this->getParametersOfWarehouse($receiptunit->warehouseid);
        if ($receiptunit->razonstatusid != 3) {
            if (! empty($parameter) && in_array('SWH-39', $parameter)) {
                if ($receiptunit->finishdate === null) {
                    return $this->errorResponse('La unidad no cuenta con fin de proceso, favor de validar. (SWH-39)');
                }
            }
        }

        if (in_array('SWH-24', $parameter)) {
            if ($request['locationid'] == null) {
                return $this->errorResponse('La ubicación es un valor obligatorio (SWH-24)');
            }
            $location = $this->getStorageLocation($request['locationid']);
            StorageLocation::updateLocation($request['locationid'], 'D', $receiptunit->boxnumber);
        } else {
            $location = $this->getLocationDummy($receiptunit->centerid);
        }

        if (empty($location)) {
            return $this->errorResponse('La bodega no cuenta con ubicación DUMMY para colocar la caja!!');
        }

        StorageLocation::updateLocation($receiptunit->locationid, 'E', '');

        if ($receiptunit->razonstatusid == 1) {
            $shipment = OrderDeliverie::searchForUnit($receiptunit->id);
            if (! empty($shipment)) {
                if (! empty($parameter) && in_array('SWH-19', (array) $parameter)) {
                    if ($shipment->statusid != 'EJ') {
                        return $this->errorResponse('El numero de embarque no se encuentra facturado (SWH-19)');
                    } else {
                        $shipment->statusid = 'DT';
                        $shipment->save();
                    }
                } else {
                    $shipment->statusid = 'DH';
                    $shipment->save();
                }
            }
        }

        if ($receiptunit->razonstatusid == 2) {
            if (in_array('SWH-31', (array) $parameter)) {
                $shipmentReceipt = ReceiptAsn::searchReceiptForUnit($receiptunit->id);
                if (! empty($shipmentReceipt) && $shipmentReceipt->unloadfinishdate === null) {
                    return $this->errorResponse('La descarga no ha finalizado: ' . $shipmentReceipt->shipmentid);
                }
            }
        }

        StorageComment::createCommentary('ReceiptUnits', $receiptunit->id, $request);
        $this->saveCheckListAutorizeUnit($parameter, $request, $receiptunit);

        $this->updateReceiptUnit($receiptunit, $location, $request);
        StorageUnitTrace::createTraceUnit($receiptunit, '11');
        $this->saveAuthorizeName($receiptunit);
    }

    public function getStorageLocation($locationid)
    {
        return StorageLocation::controlAccessUser()->whereId($locationid)->first(['zoneid', 'id']);
    }

    public function saveCheckListAutorizeUnit($parameter, Request $request, $receiptunit): void
    {
        if (in_array('SWH-40', (array) $parameter)) {
            $this->clearCheckListTransport($receiptunit->id, 'AS');
            $checklistids = $request->get('checklitsdetailid');
            $responses = $request->get('response');
            $checkid = $request->get('checklistid');

            foreach ($checklistids as $i => $checklistDetailId) {
                $checkListTransport = new CheckListTransport;
                $checkListTransport->centerid = $receiptunit->centerid;
                $checkListTransport->checklistid = $checkid[$i];
                $checkListTransport->receiptunitid = $receiptunit->id;
                $checkListTransport->checklitsdetailid = $checklistDetailId;
                $checkListTransport->verified = $responses[$i];
                $checkListTransport->type = 'AS';
                $checkListTransport->save();
            }
        }
    }

    public function saveAuthorizeName($receiptunit): void
    {
        ReceiptUnitKey::updateOrCreate([
            'centerid' => $receiptunit->centerid,
            'warehouseid' => $receiptunit->warehouseid,
            'folionumber' => $receiptunit->id,
        ], [
            'nameautoriza' => Auth::user()->name,
        ]);
    }

    private function updateReceiptUnit(ReceiptUnit $receiptunit, StorageLocation $location, $request)
    {
        $receiptunit->toreceivedate = now();
        $receiptunit->autorizaoutputdate = now();
        $receiptunit->statusid = 'OU';
        $receiptunit->zoneid = $location->zoneid;
        $receiptunit->locationid = $location->id;
        $receiptunit->save();
    }
}
