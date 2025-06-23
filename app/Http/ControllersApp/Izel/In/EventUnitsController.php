<?php

namespace App\Http\ControllersApp\Izel\In;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Izel\SaveEventsRequest;
use App\Models\Configuration\EDI\SystemEventWebServiceIzel;
use App\Models\Receipt\ReceiptAsn;
use App\Models\Shipments\EdiShipment;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\StorageUnitTrace;
use App\Traits\ResponseApi;

class EventUnitsController extends Controller
{
    use ResponseApi;

    //13,ENRAMPE DE UNIDAD
    //14,INICIA PROCESO
    //15,TERMINA PROCESO


    public function saveEventsUnits(SaveEventsRequest $request)
    {
        $folio = $this->searchReceiptUnit($request);
        if (empty($folio)) {
            return $this->handleError($request, 'El folio se encuentra en estatus incorrecto, favor de validar.', '-1');
        }
        $message = $this->validateCaseConditions($request['typeid'], $folio, $request['createdate']);
        if ($message) {
            return $this->handleError($request, $message, '-1');
        }
        $folio->save();
        $responseJson = $this->getResponseSuccessWithMessage('saveeventsunits', [
            'folionumber' => $folio->id,
            'barcode' => $folio->barcode,
            'ensnare' => $folio->ensnare,
            'startdate' => $folio->startdate,
            'finishdate' => $folio->finishdate,
            'statusname' => $folio->status->name,
            'statusid' => $folio->statusid,
        ], 'Proceso realizado con éxito.', '200');
        $this->saveLogWebService($request, $responseJson, 'Proceso realizado con exito.', '0');
        return $responseJson;
    }


    private function validateCaseConditions($typeId, $folio, $createDate): ?string
    {
        switch ($typeId) {
            case '13':
                if ($folio->ensnare != null) {
                    return 'El folio ya tiene enrampe asignado.';
                }
                $folio->ensnare = $createDate;
                $folio->statusid = 'EN';
                StorageUnitTrace::createTraceUnit($folio, '13');
                break;

            case '14':
                if ($folio->ensnare == null) {
                    return 'El folio no tiene enrampe asignado.';
                }
                if ($folio->startdate != null) {
                    return 'El folio ya cuenta con inicio de proceso.';
                }
                $folio->startdate = $createDate;
                $folio->statusid = 'EP';
                StorageUnitTrace::createTraceUnit($folio, '14');
                $this->registeredEventsInSchedulle($folio,$typeId);
                break;

            case '15':
                if ($folio->ensnare == null) {
                    return 'El folio no tiene enrampe asignado.';
                }
                if ($folio->startdate == null) {
                    return 'El folio no tiene inicio de proceso asignado.';
                }
                if ($folio->finishdate != null) {
                    return 'El folio ya cuenta con fin de proceso.';
                }
                $folio->finishdate = $createDate;
                $folio->statusid = 'FI';
                StorageUnitTrace::createTraceUnit($folio, '15');
                $this->registeredEventsInSchedulle($folio,$typeId);
                break;

            default:
                return 'Tipo de evento no válido.';
        }

        return null;
    }

    private function handleError(SaveEventsRequest $request, string $message, string $code): \Illuminate\Http\JsonResponse
    {
        $responseJson = $this->getResponseWithErrors('saveeventsunits', $message);
        $this->saveLogWebService($request, $responseJson, $message, $code);
        return $responseJson;
    }


    public function saveLogWebService($request, $jsonResponse, $message, $code): void
    {
        SystemEventWebServiceIzel::create([
            'centerid' => $request['centerid'],
            'warehouseid' => $request['warehouseid'],
            'folionumber' => $request['folionumberid'],
            'servicename' => 'saveeventsunits',
            'processdate' => $request['createdate'],
            'message' => $message,
            'code' => $code,
            'created_at' => now(),
            'type' => 'Izel',
            'value01' => 'Event - ' . $request['typeid'],
            'messagejson' => $request->all(),
            'messagejsonresponse' => $jsonResponse,
        ]);
    }

    /**
     * @param SaveEventsRequest $request
     * @return mixed
     */
    public function searchReceiptUnit(SaveEventsRequest $request)
    {
        return ReceiptUnit::controlAccessUser()
            ->leftJoin('systemcenterdistributions', 'receiptunits.centerid', '=', 'systemcenterdistributions.centerid')
            ->leftJoin('systemwarehouses', 'receiptunits.warehouseid', '=', 'systemwarehouses.warehouseid')
            ->where('systemcenterdistributions.code', $request['centerid'])
            ->where('systemwarehouses.auxiliary01', $request['warehouseid'])
            ->where('receiptunits.id', $request['folionumberid'])
            ->whereNotIn('receiptunits.statusid', ['OU', 'TR'])->first();
    }


    public function registeredEventsInSchedulle($folio, $typeid)
    {
        if ($folio->razonstatusid == 1) {
            $orderDelivery = OrderDeliverie::controlAccessUser()->where('folionumber', $folio->id)
                ->whereNotIn('statusid', ['CA', 'DH'])
                ->first();

            if (!empty($orderDelivery)) {
                $parameter = $this->getParametersOfWarehouse($orderDelivery->warehouseid);
                if ($typeid == 14) {
                    OrderDeliverie::updateStatusForStartProcess($orderDelivery);
                    if (in_array('SWH-21', (array)$parameter)) {
                        EdiShipment::createEDIForEventToShipment($orderDelivery, 'START_LOAD');
                    }
                }
                if ($typeid == 15) {
                    OrderDeliverie::updateStatusForFinishProcess($orderDelivery);
                    if (in_array('SWH-21', (array)$parameter)) {
                        EdiShipment::createEDIForEventToShipment($orderDelivery, 'END_LOAD');
                    }
                }
                $orderDelivery->save();
            }
        }

        if ($folio->razonstatusid == 2) {
            if ($typeid == 14) {
                ReceiptAsn::startProcessReceipt($folio->id);
            }
            if ($typeid == 15) {
                ReceiptAsn::finishProcessReceipt($folio->id);
            }
        }

    }


}
