<?php

namespace App\Traits\Web\Yard\ReceiptTruck;

use App\Models\Yard\ReceiptTruck;
use App\Models\Yard\StorageLabelTruck\StorageLabelTruck;
use App\Traits\Web\General\GetParameters;
use App\Traits\Web\General\Responser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

trait QueryReceiptTruck
{
    use Responser;
    use GetParameters;

    public function getDataAll(Request $request)
    {
        $data = ReceiptTruck::controlAccessUser()
            ->with('center', 'warehouse', 'carrier', 'status')
            ->Folio($request->get('folionumber'))
            ->TruckNumber($request->get('trucknumber'))
            ->EcoNumber($request->get('econumber'))
            ->Carrier($request->get('carrierid'))
            ->Barcode($request->get('barcode'))
            ->Name($request->get('name'))
            ->Warehouse($request->get('warehouseid'))
            ->whereNotIn('receipttrucks.statusid', ['TR']);
        return $data;
    }


    //    Crear nuevo registro
    public function createNewTruck($request)
    {
        $centerid = $request['centerid'] ?? $this->getCenterForUser();
        $warehouseid = $request['warehouseid'] ?? $this->getWarehouseForUser();
        $parameter = $this->getParametersOfWarehouse($warehouseid);
        $barcode = $this->getFolioYMS($centerid);
        $truck = ReceiptTruck::create([
            'centerid' => $request['centerid'] ?? $centerid,
            'warehouseid' => $request['warehouseid'] ?? $warehouseid,
            'barcode' => $request['barcodetruck'] ?? $barcode->folionumber,
            'carrierid' => $request['carrierid'],
            'operatorname' => $request['operatorname'],
            'phone' => $request['phone'] ?? '0',
            'econumber' => $request['econumber'],
            'trucknumber' => $request['trucknumber'] ?? $request['ecotruck'],
            'brand' => $request['brand'],
            'typeoflicense' => $request['typeoflicense'],
            'validity' => $request['validity'],
            'drivernumber' => $request['drivernumber'],
        ]);
        self::createLabelFromRegisterTruck($request, $parameter, $truck);
        return $truck;
    }

//    Realizar salida del tracto
    public function generateOutTruck($receipttruck, $type)
    {
        $receipttruck->update([
            'statusid' => 'TR',
            'finishdate' => Carbon::now(),
            'updateuser' => Auth::user()->id,
            'auxiliary01' => $type
        ]);


    }


    public static function createLabelFromRegisterTruck($request, $parameter, $truck): void
    {
        if (in_array('SWH-09', (array)$parameter)) {
            if (!empty($request['barcodetruck'])) {
                $barcode = $request['barcodetruck'] ?? $truck->barcode;
                $label = StorageLabelTruck::where('barcode', '=', $barcode)->first();
                if (!empty($label)) $label->delete();
                StorageLabelTruck::create(
                    [
                        'centerid' => $request['centerid'] ?? $truck->centerid,
                        'warehouseid' => $request['warehouseid'] ?? $truck->warehouseid,
                        'boxnumber' => $request['trucknumber'] ?? $request['ecotruck'],
                        'barcode' => $barcode,
                        'econumber' => $request['econumbertruck'] ?? $request['econumber'],
                        'carrierid' => $request['carrierid'] ?? $truck->carrierid,
                        'truckid' => $request['truckid'] ?? $truck->id,
                        'typeid' => 'TR'
                    ]
                );
            }
        }
    }

}
