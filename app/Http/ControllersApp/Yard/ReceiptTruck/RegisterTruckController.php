<?php

namespace App\Http\ControllersApp\Yard\ReceiptTruck;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Yard\ReceiptTruck\CrearNuevoTractoRequest;
use App\Models\Yard\ReceiptTruck;
use App\Models\Yard\ReceiptUnit;
use App\Traits\ResponseApi;
use App\Traits\Web\Yard\ReceiptTruck\QueryReceiptTruck;
use Illuminate\Http\Request;

class RegisterTruckController extends Controller
{
    use ResponseApi;
    use QueryReceiptTruck;

//    Registra un nuevo tracto
    public function saveNewTruckInCenter(CrearNuevoTractoRequest $request)
    {
        $truckExist = ReceiptTruck::searchTruckForTruckNumber($request['trucknumber']);
        if (!empty($truckExist)) {
            return $this->getResponseWithErrors('saveNewTruckInCenter', 'El tracto ya se encuentra registrado, favor de validar.');
        }
        $truck = $this->createNewTruck($request);
        return $this->getResponseSuccess('saveNewTruckInCenter',
            [
                'folionumber' => $truck->id,
                'carriername' => $truck->carrier->name
            ]);
    }

//    Consulta de tracto para salida
    public function getDataTruckStock(Request $request)
    {
        if (empty($request['truckcode'])) {
            return $this->getResponseWithErrors('getDataTruck', 'El código del tracto o la placa  es obligatorio.');
        }
        $truck = ReceiptTruck::searchTruckForTruckNumber($request['truckcode']);
        if (empty($truck)) {
            return $this->getResponseWithErrors('getDataTruck', 'No existen datos del tracto,favor de validar de nuevo.');
        }
        return $this->getResponseSuccess('getDataTruck', [
            'carriername' => $truck->carrier->name,
            'trucknumber' => $truck->trucknumber,
            'operatorname' => $truck->operatorname,
        ]);
    }

    //    Registrar salida de unidad
    public function saveOutPutTruck(Request $request)
    {
        $receipttruck = ReceiptTruck::searchTruckForTruckNumber($request['truckcode']);
        if (empty($receipttruck)) {
            return $this->getResponseWithErrors('saveOutPutTruck', 'No existen datos del tracto.');
        }
        ReceiptUnit::controlAccessUser()->where('foliotruck',$receipttruck->id)
            ->update(['foliotruck' => null]);


        $this->generateOutTruck($receipttruck, 'Salida de tracto');
        return $this->getResponseSuccess('saveOutPutTruck', ['folio' => $receipttruck->id]);
    }

}
