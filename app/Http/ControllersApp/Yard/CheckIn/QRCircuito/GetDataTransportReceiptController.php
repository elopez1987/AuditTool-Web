<?php

namespace App\Http\ControllersApp\Yard\CheckIn\QRCircuito;

use App\Http\Controllers\Controller;
use App\Models\Yard\GenerateAccessWarehouse;
use App\Traits\GetParameters;
use App\Traits\ResponseApi;
use Illuminate\Http\Request;

class GetDataTransportReceiptController extends Controller
{
    use ResponseApi;
    use GetParameters;

    public function getDataTransportReceipt(Request $request)
    {
        if ($request['barcodebox'] == null) {
            return $this->getResponseWithErrors('getDataTransportReceipt', 'El código de barra es obligatorio');
        }
        $keyValue = GenerateAccessWarehouse::getAccessWarehouse($request['barcodebox']);
        if (empty($keyValue) || $keyValue->statuskey == 'BK') {
            return $this->getResponseWithErrors('getDataTransportReceipt', 'No existe código de acceso o ya no esta disponible favor de validar.');
        }
        if (empty($keyValue->operatorname)) {
            return $this->getResponseWithErrors('getDataTransportReceipt', 'El código del operador ya no existe, favor de validar.');
        }
        if (empty($keyValue->boxnumberbox)) {
            return $this->getResponseWithErrors('getDataTransportReceipt', 'El código de la caja ya no existe, favor de validar.');
        }
        if (empty($keyValue->boxnumbertruck)) {
            return $this->getResponseWithErrors('getDataTransportReceipt', 'El código del tracto ya no existe, favor de validar');
        }
        $boxstatus = $this->getStatusBoxForBarcode($keyValue->barcodebox);
        if (!empty($boxstatus)) {
            return $this->getResponseWithErrors('getDataTransportReceipt', 'La caja se encuentra en patio.');
        }
        $truckStatus = $this->getStatusTruckForBarcode($keyValue->barcodetruck);
        if (!empty($truckStatus)) {
            return $this->getResponseWithErrors('getDataTransportReceipt', 'El tracto se encuentra en patio.');
        }
        return $this->getResponseSuccess('getDataTransportReceipt', [
            'carriername' => $keyValue->carriername,
            'barcodeoperator' => $keyValue->barcodeoperator,
            'operador' => $keyValue->operatorname,
            'barcodebox' => $keyValue->barcodebox,
            'boxnumber' => $keyValue->boxnumberbox,
            'barcodetruck' => $keyValue->barcodetruck,
            'trucknumber' => $keyValue->boxnumbertruck]);
    }


}
