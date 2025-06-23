<?php

namespace App\Http\Controllers\Services\Yard;

use App\Http\Controllers\Controller;
use App\Http\Resources\Yard\ReceiptUnitsResource;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\ReceiptUnitStock;
use App\Traits\GetParameters;
use App\Traits\ResponseApi;
use App\Traits\Web\Evidence\QueryStorageEvidences;
use Illuminate\Http\Request;

class GetDataBoxController extends Controller
{
    use ResponseApi;
    use QueryStorageEvidences;
    use GetParameters;

//    Buscar la unidad por codigo de barra o placa de caja en la existencia
    public function getDataBox(Request $request)
    {
        if ($request['barcode'] == null && $request['boxnumber'] == null) {
            return $this->getResponseWithErrors('getDataBox', 'El código de barra o placa de caja son necesarios.');
        }
        $receiptUnit = ReceiptUnit::controlAccessUser()->whereNotIn('statusid', ['TR'])
            ->Code($request->get('barcode'))
            ->Box($request->get('boxnumber'))
            ->Folio($request->get('folioid'))->first();
        if (empty($receiptUnit)) {
            return $this->getResponseWithErrors('getDataBox', 'No existen registros de la caja');
        }
        return $this->showOne('getDataBox', new ReceiptUnitsResource($receiptUnit));
    }


    public function getFolio(Request $request)
    {
        if ($request['folioid'] == null) {
            return $this->getResponseWithErrors('getFolio', 'Ingrese un número de folio.');
        }
        $receiptUnit = ReceiptUnit::controlAccessUser()->whereNotIn('statusid', ['TR'])
            ->Code($request->get('barcode'))
            ->Box($request->get('boxnumber'))
            ->Folio($request->get('folioid'))->first();
        if ($receiptUnit == null) {
            return $this->getResponseWithErrors('getFolio', 'No existen registros con el filtro ingresado.');
        }
        return $this->showOne('getFolio', new ReceiptUnitsResource($receiptUnit));
    }


//    Consulta de unidad para evidencia de proceso de carga o descarga
    public function getFolioForEvidenceProcess(Request $request)
    {
        if ($request['folioid'] == null) {
            return $this->getResponseWithErrors('getFolio', 'Ingrese un número de folio.');
        }
        $receiptunit = ReceiptUnit::searchForId($request['folioid']);
        if (empty($receiptunit)) {
            return $this->getResponseWithErrors('getFolioForProcess', 'No existen registros con el filtro ingresado.');
        }
        return $this->showOne('getFolioForProcess', new ReceiptUnitsResource($receiptunit));
    }


//    Conculsta de unidad para toma de evidencia en proceso de entrada
    public function getFolioCheckIn(Request $request)
    {
        if ($request['folioid'] == null) {
            return $this->getResponseWithErrors('getFolioCheckIn', 'Ingrese un número de folio.');
        }
        $receiptUnit = ReceiptUnit::controlAccessUser()->where('receiptunits.statusid', '=', 'QI')->Folio($request->get('folioid'))->first();
        if (empty($receiptUnit)) {
            return $this->getResponseWithErrors('getFolioCheckIn', 'La unidad no se encuentra en la existencia.');
        }
        if ($receiptUnit->statusid != 'QI') {
            return $this->getResponseWithErrors('getFolioCheckIn', 'La unidad se encuentra en un estatus diferente a ENTRADA AUTORIZADA.');
        }
        return $this->showOne('getFolioCheckIn', new ReceiptUnitsResource($receiptUnit));
    }


//    --Consulta de unidad para registro de salida
    public function getFolioCheckOut(Request $request)
    {
        if (is_null($request['barcode'])) {
            return $this->getResponseWithErrors('getFolioCheckOut', 'Ingrese el codigo de barra de la caja.');
        }
        $receiptUnit = ReceiptUnit::controlAccessUser()->whereNotIn('receiptunits.statusid', ['TR'])
            ->Code($request->get('barcode'))->first();
        if (empty($receiptUnit)) {
            return $this->getResponseWithErrors('getFolioCheckOut', 'No existen registros de la unidad ingresada, favor de validar.');
        }
        if (empty($receiptUnit->foliotruck)){
            return $this->getResponseWithErrors('getFolioCheckOut', 'La unidad no cuenta con folio de tracto acoplado, favor de validar con el area de transporte.');
        }
        $paramCenter = $this->getParametersOfCenter($receiptUnit->centerid);
        if (!empty($paramCenter) && in_array('SDC-12', (array)$paramCenter)) {
            return $this->showOne('getFolioCheckOut', new ReceiptUnitsResource($receiptUnit));
        }
        $paramWarehose = $this->getParametersOfWarehouse($receiptUnit->warehouseid);
        if (!empty($paramWarehose) && in_array('SWH-22', (array)$paramWarehose)) {
            return $this->showOne('getFolioCheckOut', new ReceiptUnitsResource($receiptUnit));
        }
        $paramCarrier = $this->getParametersOfCarrier($receiptUnit->carrierid);
        if (!empty($paramCarrier) && in_array('SCR-01', (array)$paramCarrier)) {
            return $this->showOne('getFolioCheckOut', new ReceiptUnitsResource($receiptUnit));
        }
        if ($receiptUnit->statusid != 'OU') {
            return $this->getResponseWithErrors('getFolioCheckOut', 'La caja no cuenta con autorización de salida.');
        }
        return $this->showOne('getFolioCheckOut', new ReceiptUnitsResource($receiptUnit));
    }


    public function getListFolios()
    {
        $data = ReceiptUnitStock::controlAccessUser()->get();
        return $this->getResponseSuccess('getListFolios', $data);
    }


    public function getShipments()
    {
        $shipments = $this->getShipmentForDashboard();
        $shipments = array_map(function ($shipment) {
            return ['shipment' => $shipment];
        }, $shipments);
        return response()->json(['data' => $shipments, 'status' => 'success']);
    }


}
