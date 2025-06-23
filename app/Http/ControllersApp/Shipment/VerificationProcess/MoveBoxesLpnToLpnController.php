<?php

namespace App\Http\ControllersApp\Shipment\VerificationProcess;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Shipment\VerificationProcess\SaveMoveLpnToLpnRequest;
use App\Http\Services\Shipment\ServicePick;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\ShipmentStock;
use App\Traits\ResponseApi;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MoveBoxesLpnToLpnController extends Controller
{
    use ResponseApi;

    private ServicePick $servicePick;

    public function __construct(ServicePick $servicePick)
    {
        $this->servicePick = $servicePick;
    }

    public function index(SaveMoveLpnToLpnRequest $request)
    {
        DB::beginTransaction();
        try {
            $shipment = $this->getShipment($request['shipmentid']);
            if (empty($shipment)) {
                return $this->getResponseWithErrors('getTotalQuantityForSkuForLpns', 'El número de embarque no existe, favor de validar.');
            }
            $lpn = ShipmentStock::where('orderid', $shipment->id)
                ->where('lpnid', $request['lpnid'])
                ->where('partnumberid', $request['partnumberid'])->first();
            if (empty($lpn)) {
                return $this->getResponseWithErrors('saveMoveBoxToLpn', 'No existe el Lpn Original ingresado, favor de validar.');
            }
            $lpnRec = ShipmentStock::where('orderid', $shipment->id)->where('lpnid', $request['lpnidrec'])->first();
            if (empty($lpnRec)) {
                return $this->getResponseWithErrors('saveMoveBoxToLpn', 'El número de lpn que recibe no se encuentra registrado');
            }
            $this->servicePick->saveMoveBoxToLpn($request, $shipment, $lpn);
            DB::commit();
            return $this->getResponseSuccess('saveMoveBox', 'Cajas movidas con exito.');
        } catch (Exception $e) {
            DB::rollback();
            Log::error('Se produjo un error: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString(),
            ]);
            return $this->getResponseWithErrors('saveMoveBox', 'Error al procesar la solicitud, favor de intentarlo mas tarde.');
        }
    }


    public function getShipment($shipmentid)
    {
        return OrderDeliverie::controlAccessUser()->where('shipmentid', $shipmentid)->whereNotIn('statusid', ['DH', 'CA'])->first();
    }



}
