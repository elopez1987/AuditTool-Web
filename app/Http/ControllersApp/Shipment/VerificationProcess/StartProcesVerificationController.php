<?php

namespace App\Http\ControllersApp\Shipment\VerificationProcess;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Shipment\VerificationProcess\SearchShipmenVerificationRequest;
use App\Http\Services\ServiceShipmentCheck;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\ShipmentCheck;
use App\Traits\ResponseApi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StartProcesVerificationController extends Controller
{
    use ResponseApi;

    public function index(SearchShipmenVerificationRequest $request)
    {
        $orderDelivery = self::getShipmentForVerify($request['shipmentid']);
        if (empty($orderDelivery)) {
            return $this->getResponseWithErrors('saveStartCheckShipment', 'No existe el número de embarque o se encuentra en estatus no permitido.');
        }
        DB::beginTransaction();
        try {
            $shipmentCheck = self::saveStartVerificationShipment($orderDelivery);
            DB::commit();
            return $this->getResponseSuccess('saveStartCheckShipment', $shipmentCheck);
        } catch (Exception $e) {
            DB::rollback();
            return $this->getResponseWithErrors('saveStartCheckShipment', 'Error al procesar la solicitud, intente mas tarde.');
        }
    }

    static function saveStartVerificationShipment($orderDelivery)
    {
        $shipmentCheck = ShipmentCheck::where('orderid', '=', $orderDelivery->id)->first();
        if (!empty($shipmentCheck) && $shipmentCheck->statusid != 'VY') {
            $shipmentCheck->startdate = now();
            $shipmentCheck->createuser = Auth::user()->username;
            $shipmentCheck->statusid = 'VY';
            $shipmentCheck->save();

            $orderDelivery->statusid = 'VY';
            $orderDelivery->save();
        }
        return $shipmentCheck;
    }


    static function getShipmentForVerify($shipmentid)
    {
        return OrderDeliverie::controlAccessUser()
            ->where('shipmentid', trim($shipmentid))
            ->whereNotIn('statusid', ['CA', 'DH', 'AP'])->first();
    }


}
