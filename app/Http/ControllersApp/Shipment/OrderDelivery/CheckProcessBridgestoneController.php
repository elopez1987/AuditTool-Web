<?php

namespace App\Http\ControllersApp\Shipment\OrderDelivery;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaveShipmentCheckTotalRequest;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\OrderDelivery\StorageShipmentCheckTotal;
use App\Models\Yard\StorageLocation;
use App\Traits\ResponseApi;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CheckProcessBridgestoneController extends Controller
{
    use ResponseApi;

    public function index(SaveShipmentCheckTotalRequest $request)
    {
        \Log::info($request->all());
        $orderDelivery = OrderDeliverie::controlAccessUser()->where('shipmentid', $request['shipmentid'])
            ->whereNotIn('statusid', ['CA', 'DH'])->first();
        $location = StorageLocation::controlAccessUser()->where('barcode', $request['locationid'])->first();
        if (empty($location)) {
            return $this->getResponseWithErrors('saveShipmentCheckTotal', 'La ubicación no existe, favor de validar.');
        }
        list($keyoperator, $keyguard, $keysupervisor) = $this->saveKeysSupervisorAndGuard($request['keyoperator'], $request['keyguard'], $request['keysupervisor'], $orderDelivery);

        StorageShipmentCheckTotal::updateOrCreate([
            'centerid' => $orderDelivery->centerid,
            'warehouseid' => $orderDelivery->warehouseid,
            'orderid' => $orderDelivery->id,
        ], [
            'quantity' => $request['quantity'],
            'locationid' => $location->id,
            'accessories' => $request['accesories'],
            'changes' => $request['changes'],
            'startprocess' => $request['startprocess'],
            'finishprocess' => $request['finishprocess'],
            'keyoperator' => $keyoperator,
            'keyguard' => $keyguard,
            'keysupervisor' => $keysupervisor,
        ]);
        return $this->getResponseWithSuccess('', 'Registro guardado con exito');
    }


    public function saveKeysSupervisorAndGuard($keyoperator, $keyguard, $keysupervisor, $orderDelivery): array
    {
        $image = $keyoperator;
        $image = str_replace('data:image/png;base64,', '', $image);
        $image = str_replace(' ', '+', $image);
        $imageName = $orderDelivery->id . '-' . Str::random(15) . '.png';
        File::put(public_path('storage/public/' . $orderDelivery->centerid . '/EvidenceShipment/' . $imageName), base64_decode($image));
        $keyoperator = 'public/' . $orderDelivery->centerid . '/EvidenceShipment/' . $imageName;

        $image = $keyguard;
        $image = str_replace('data:image/png;base64,', '', $image);
        $image = str_replace(' ', '+', $image);
        $imageName = $orderDelivery->id . '-' . Str::random(15) . '.png';
        File::put(public_path('storage/public/' . $orderDelivery->centerid . '/EvidenceShipment/' . $imageName), base64_decode($image));
        $keyguard = 'public/' . $orderDelivery->centerid . '/EvidenceShipment/' . $imageName;

        $image = $keysupervisor;
        $image = str_replace('data:image/png;base64,', '', $image);
        $image = str_replace(' ', '+', $image);
        $imageName = $orderDelivery->id . '-' . Str::random(15) . '.png';
        File::put(public_path('storage/public/' . $orderDelivery->centerid . '/EvidenceShipment/' . $imageName), base64_decode($image));
        $keysupervisor = 'public/' . $orderDelivery->centerid . '/EvidenceShipment/' . $imageName;
        return array($keyoperator, $keyguard, $keysupervisor);
    }

}
