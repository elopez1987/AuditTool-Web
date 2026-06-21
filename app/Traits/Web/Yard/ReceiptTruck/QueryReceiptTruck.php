<?php

namespace App\Traits\Web\Yard\ReceiptTruck;

use App\Models\Yard\ReceiptTruck;
use App\Traits\Web\General\GetParameters;
use App\Traits\Web\General\Responser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

trait QueryReceiptTruck
{
    use GetParameters;
    use Responser;

    public function getDataAll(Request $request)
    {
        return ReceiptTruck::controlAccessUser()
            ->with(['center:centerid,name,description', 'warehouse:warehouseid,name', 'carrier:id,name',
                'status:statusid,name,auxiliary01'])
            ->select([
                'receipttrucks.id',
                'receipttrucks.centerid',
                'receipttrucks.warehouseid',
                'receipttrucks.carrierid',
                'receipttrucks.trucknumber',
                'receipttrucks.econumber',
                'receipttrucks.operatorname',
                'receipttrucks.phone',
                'receipttrucks.barcode',
                'receipttrucks.entrydate',
            ])
            ->Folio($request->input('folionumber'))
            ->TruckNumber($request->input('trucknumber'))
            ->EcoNumber($request->input('econumber'))
            ->Carrier($request->input('carrierid'))
            ->Barcode($request->input('barcode'))
            ->Name($request->input('name'))
            ->Warehouse($request->input('warehouseid'))
            ->whereNotIn('receipttrucks.statusid', ['TR']);
    }

    //    Crear nuevo registro
    public function createNewTruck($request)
    {
        $barcode = $this->getFolioYMS($request->input('centerid'));

        return ReceiptTruck::create([
            'centerid' => $request['centerid'],
            'warehouseid' => $request['warehouseid'],
            'barcode' => $barcode->folionumber,
            'carrierid' => $request['carrierid'],
            'operatorname' => $request['operatorname'],
            'phone' => $request['phone'] ?? '0',
            'econumber' => $request['ecotruck'],
            'trucknumber' => $request['trucknumber'],
            'brand' => $request['brand'],
            'typeoflicense' => $request['typeoflicense'],
            'validity' => $request['validity'],
            'drivernumber' => $request['drivernumber'],
        ]);
    }

    //    Realizar salida del tracto
    public function generateOutTruck($receipttruck, $type)
    {
        $receipttruck->update([
            'statusid' => 'TR',
            'finishdate' => Carbon::now(),
            'updateuser' => Auth::user()->id,
            'auxiliary01' => $type,
        ]);
    }
}
