<?php

namespace App\Http\ControllersApp\Yard\ReceiptTruck;

use App\Http\Controllers\Controller;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Models\Yard\ReceiptTruck;
use App\Traits\ResponseApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TruckController extends Controller
{
    use ResponseApi;

//    -Consulta de reingreso de tracto
    public function getDataTruck(Request $request)
    {
        if (empty($request['truckcode'])) {
            return $this->getResponseWithErrors('getDataTruck', 'El código del tracto es obligatorio.');
        }
        $truck = ReceiptTruck::controlAccessUser()
            ->where('receipttrucks.trucknumber', '=', $request['truckcode'])
            ->where('receipttrucks.statusid', '=', 'TR')->get()->last();
        if (empty($truck)) {
            return $this->getResponseWithErrors('getDataTruck', 'No existen datos del tracto.');
        }
        return $this->getResponseSuccess('getDataTruck',
            [
                'id' => $truck->id,
                'carriername' => $truck->carrier->name,
                'trucknumber' => $truck->trucknumber
            ]);
    }

//    -Guarda el reingreso de tracto
    public function saveRegisterTruck(Request $request)
    {
        $truck = ReceiptTruck::searchForIdTruck($request['id']);
        $truckStock = ReceiptTruck::searchTruckForTruckNumber($truck->trucknumber);
        if (!empty($truckStock)) {
            return $this->getResponseWithErrors('saveRegisterTruck', 'El tracto se encuentra en patio.');
        }
        $receipttruck = $this->createNewTruck($truck, $request);
        return $this->getResponseSuccess('saveRegisterTruck', ['trucknumber' => $receipttruck->trucknumber]);
    }

    public function createNewTruck($truck, $request)
    {
        $centerid = SecurityAccessWarehouse::where('username', Auth::user()->username)
            ->value('centerid');
        $warehouseid = SecurityAccessWarehouse::where('username', Auth::user()->username)
            ->value('warehouseid');
        return ReceiptTruck::create([
            'centerid' => $centerid,
            'warehouseid' => $warehouseid,
            'barcode' => $truck->barcode,
            'carrierid' => $truck->carrierid,
            'operatorname' => $request['operatorname'],
            'phone' => $request['phone'] ?? '0',
            'econumber' => $truck->econumber,
            'trucknumber' => $truck->trucknumber,
            'brand' => $truck->brand,
            'typeoflicense' => $request['typeoflicense'] ?? '',
            'validity' => $request['validity'] ?? '',
            'drivernumber' => $request['drivernumber'] ?? '0',
        ]);
    }


}
