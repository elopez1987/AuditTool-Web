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
        return ReceiptTruck::controlAccessUser()
            ->with(['center', 'warehouse', 'carrier', 'status'])
            ->Folio($request->get('folionumber'))
            ->TruckNumber($request->get('trucknumber'))
            ->EcoNumber($request->get('econumber'))
            ->Carrier($request->get('carrierid'))
            ->Barcode($request->get('barcode'))
            ->Name($request->get('name'))
            ->Warehouse($request->get('warehouseid'))
            ->whereNotIn('receipttrucks.statusid', ['TR']);
    }

    //    Crear nuevo registro
    public function createNewTruck($request)
    {
        $parameter = $this->getParametersOfWarehouse($request['warehouseid']);
        $truck = ReceiptTruck::create([
            'centerid' => $request['centerid'],
            'warehouseid' => $request['warehouseid'],
            'barcode' => $request['barcodetruck'],
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
        if (!empty($parameter) && in_array('SWH-09', (array)$parameter)) {
            self::createLabelFromRegisterTruck($parameter, $truck);
        }

        return $truck;
    }


    public static function createLabelFromRegisterTruck($truck): void
    {
        StorageLabelTruck::updateOrCreate(
            [
                'barcode' => $truck->barcode,
            ],
            [
            'centerid' => $truck->centerid,
            'warehouseid' => $truck->warehouseid,
            'boxnumber' => $truck->trucknumber,
            'econumber' => $truck->econumber,
            'carrierid' => $truck->carrierid,
            'typeid' => 'TR',
        ]
        );
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
