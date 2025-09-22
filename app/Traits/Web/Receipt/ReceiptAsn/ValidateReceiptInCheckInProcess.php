<?php

namespace App\Traits\Web\Receipt\ReceiptAsn;

use App\Models\Receipt\ReceiptAsn;
use Carbon\Carbon;
use Illuminate\Http\Request;

trait ValidateReceiptInCheckInProcess
{
    public function validateReceiptInCheckIn($request, $receiptunit, $parameter, $platform)
    {
        if ($request['razonstatusid'] !== '2') {
            return null;
        }

        $receipt = ReceiptAsn::searchReceiptInCheckIn($request['shipmentid']);

        $validationResult = $this->validateReceiptWithParameter($receipt, $request, $parameter, $platform);
        if ($validationResult) {
            return $validationResult;
        }

        if (! empty($receipt)) {
            self::updateTransportInReceiptAsn($request, $receiptunit, $receipt);
        }

        if (! empty($parameter) && in_array('SWH-07', (array) $parameter)) {
            self::createNewReceiptAsnInCheckIn($request, $receiptunit);
        }

        return null;
    }

    public function createNewReceiptAsnInCheckIn($request, $receiptunit)
    {
        $turnAndDate = self::getTurnAndWorkday();
        ReceiptAsn::create([
            'centerid' => $receiptunit->centerid,
            'warehouseid' => $receiptunit->warehouseid,
            'workday' => $turnAndDate['workday'],
            'turnid' => $turnAndDate['turn'],
            'shipmentid' => $request['shipmentid'],
            'addasndate' => Carbon::now(),
            'arriveddate' => Carbon::now(),
            'providerid' => $request['providerid'],
            'folionumber' => $receiptunit->id,
            'carrierid' => $receiptunit->carrierid,
            'razonstatusid' => '19',
            'stockcategoryid' => '1',
            'statusid' => 'RE',
        ]);
    }

    private function validateReceiptWithParameter($receipt, $request, $parameter, $platform)
    {
        if (empty($parameter) || ! in_array('SWH-02', (array) $parameter)) {
            return null;
        }

        if (empty($receipt) && $platform === 'WEB') {
            return $this->errorResponse('El número de recibo no se encuentra programado, favor de validar.');
        }

        if (! empty($receipt) && $receipt->providerid !== $request['providerid'] && $platform === 'WEB') {
            return $this->errorResponse('El proveedor es diferente al programado, favor de validar.');
        }

        return null;
    }

    private function updateTransportInReceiptAsn(Request $request, $receiptunit, $receipt)
    {
        $receipt->arriveddate = Carbon::now();
        $receipt->folionumber = $receiptunit->id;
        $receipt->carrierid = $receiptunit->carrierid;
        $receipt->statusid = 'RE';
        $receipt->save();
    }
}
