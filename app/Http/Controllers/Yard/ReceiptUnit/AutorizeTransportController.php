<?php

namespace App\Http\Controllers\Yard\ReceiptUnit;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Yard\ReceiptUnit\AutorizeUnitRequest;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\StorageUnitTrace;
use App\Traits\Web\EDI\RegisterEdiShipment;
use App\Traits\Web\General\GetParameters;
use App\Traits\Web\General\Responser;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AutorizeTransportController extends Controller
{
    use GetParameters;
    use RegisterEdiShipment;
    use Responser;

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
        $receiptunit = ReceiptUnit::searchForId($request->input('folionumber'));
        if (empty($receiptunit)) {
            return response()->json(['error' => 'El número de folio no existe!!'], 404);
        }

        $receiptunit = $this->generateOutPutBox(
            $receiptunit,
            $request->input('razonstatusid'),
            $request->input('centerid'),
            $request->input('warehouseid')
        );

        return response()->json([
            'id' => $receiptunit->id,
            'entrydate' => $receiptunit->entrydate,
        ]);
    }

    public function generateOutPutBox($receiptunit, $razonstatusid, $centerid, $warehouseid)
    {
        $receiptunit->entrydate = Carbon::now();
        $receiptunit->updateuser = Auth::user()->id;
        $receiptunit->razonstatusid = $razonstatusid;
        $receiptunit->centerid = $centerid;
        $receiptunit->warehouseid = $warehouseid;
        $receiptunit->statusid = 'IN';
        $receiptunit->save();
        StorageUnitTrace::createTraceUnit($receiptunit, $razonstatusid);

        return $receiptunit;
    }
}
