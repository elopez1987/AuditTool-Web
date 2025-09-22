<?php

namespace App\Http\Controllers\Yard\ReceiptUnit;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Yard\ReceiptUnit\AutorizeUnitRequest;
use App\Models\General\StorageComment;
use App\Models\Yard\CheckListTransport;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\ReceiptUnitTicket;
use App\Models\Yard\StorageCheckListDetail;
use App\Models\Yard\StorageUnitTrace;
use App\Traits\Web\General\GetParameters;
use App\Traits\Web\General\Responser;
use App\Traits\Web\Izel\RegisterEdiShipment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AutorizeTransportController extends Controller
{
    use RegisterEdiShipment;
    use Responser;
    use GetParameters;

    public function autorizetransport()
    {
        $this->authorize('receiptunits.autorizeunit');
        $centers = $this->getCenterForSelect();
        $warehouses = $this->getWarehouseForSelect();
        $rason = $this->getRasonForCodes([1, 3]);
        return view('Yards.AutorizeTransport.index', compact('centers', 'warehouses', 'rason'));
    }

    public function autoizetransportstore(AutorizeUnitRequest $request)
    {
        $receiptunit = ReceiptUnit::searchForId($request['folionumber']);
        if (empty($receiptunit)) {
            return $this->responseSuccessAjax('El número de folio no existe!!', 'autoizetransportstore');
        }
        $parameter = $this->getParametersOfWarehouse($receiptunit->warehouseid);
        $receiptunit = $this->generateOutPutBox($receiptunit, '22');
        $this->addNewEdiForIzel($receiptunit, $parameter, 'OUT');
        $receiptunitNew = $this->getReceiptunitNew($request, $receiptunit);
        $this->addNewEdiForIzel($receiptunitNew, $parameter, 'IN');
        $this->getFolioClient($receiptunitNew->centerid, $receiptunitNew->id, 'SHT');
        ReceiptUnitTicket::closeTicket($receiptunit->id, $request);
        StorageUnitTrace::createTraceUnit($receiptunitNew, '1');
        return response()->json($receiptunitNew);
    }

    public function generateOutPutBox($receiptunit, $razonstatusid)
    {
        $receiptunit->outputdate = Carbon::now();
        $receiptunit->updateuser = Auth::user()->id;
        $receiptunit->statusid = 'TR';
        $receiptunit->save();
        StorageUnitTrace::createTraceUnit($receiptunit, $razonstatusid);
        return $receiptunit;
    }

    public function getListDetails(ReceiptUnit $receiptunitNew)
    {
        return StorageCheckListDetail::whereCenterid($receiptunitNew->centerid)
            ->whereHas('checklist', function ($q) {
                $q->where('type', 'IN');
                $q->where('statusid', 'E');
            })->get();
    }

    public function getVerified(ReceiptUnit $receiptunitNew)
    {
        return CheckListTransport::whereReceiptunitid($receiptunitNew->id)
            ->select('checklitsdetailid')->get();
    }

    private function getReceiptunitNew(Request $request, $receiptunit)
    {
        $receiptunitNew = ReceiptUnit::create([
            'centerid' => $request['centerid'],
            'warehouseid' => $request['warehouseid'],
            'barcode' => $receiptunit->barcode,
            'shipmentid' => $request['shipmentid'] ?? '0',
            'carrierid' => $receiptunit->carrierid,
            'truckid' => $receiptunit->truckid,
            'containerseal' => '0',
            'operatorname' => $receiptunit->operatorname,
            'entrydate' => now(),
            'locationid' => $receiptunit->locationid,
            'unittypeid' => $receiptunit->unittypeid,
            'trucknumber' => $receiptunit->trucknumber,
            'boxnumber' => $receiptunit->boxnumber,
            'type' => 'Box',
            'zoneid' => $receiptunit->zoneid,
            'phone' => $receiptunit->phone,
            'razonstatusid' => $request['razonstatusid'],
            'unitstatusid' => '1',
            'drivernumber' => '0',
            'truckcategoryid' => $receiptunit->truckcategoryid,
            'createuser' => Auth::user()->id,
            'statusid' => 'QI',
            'auxiliary02' => 'Folio generado de' . ' ' . $receiptunit->id,
            'auxiliary03' => 'ST',
            'auxiliary01' => $receiptunit->auxiliary01,
            'foliotruck' => $receiptunit->foliotruck,
        ]);
        $receiptunitNew->save();

        StorageComment::create([
            'modeltype' => 'ReceiptUnits',
            'modelid' => $receiptunitNew->id,
            'createuser' => Auth::user()->id,
            'comment' => 'Folio generado automaticamente de' . ' ' . $receiptunit->id,

        ]);
        return $receiptunitNew;
    }
}
