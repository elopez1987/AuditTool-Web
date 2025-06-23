<?php

namespace App\Http\Controllers\Services\Yard;

use App\Http\Controllers\Controller;
use App\Models\Yard\ReceiptTruck;
use App\Traits\ResponseApi;
use Illuminate\Http\Request;

class GetDataTruckController extends Controller
{
    use ResponseApi;

    public function getDataTruckSearch(Request $request)
    {
        if (empty($request['boxnumber']) && empty($request['barcode'])) {
            return $this->getResponseWithErrors('getDataTruckSearch', 'El código de barra o placa del tracto son necesarios.');
        }
        $truck = ReceiptTruck::controlAccessUser()
            ->whereNotIn('receipttrucks.statusid', ['TR'])
            ->TruckNumber($request->get('boxnumber'))
            ->Barcode($request->get('barcode'))->first();
        if (empty($truck)) {
            return $this->getResponseWithErrors('', 'No existen registros con los valores ingresados.');
        }
        return $this->getResponseSuccess('getDataTruckSearch', [
            'centerid' => $truck->center ? $truck->center->name : '',
            'warehouse' => $truck->warehouse ? $truck->warehouse->name : '',
            'folionumber' => (string) $truck->id,
            'barcode' => $truck->barcode,
            'trucknumber' => $truck->trucknumber,
            'econumber' => $truck->econumber,
            'operatorname' => $truck->operatorname,
            'carriername'  => $truck->carrier ? $truck->carrier->name : '',
        ]);
    }


}
