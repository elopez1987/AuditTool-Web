<?php

namespace App\Http\ControllersApp\Shipment\LoadProcess;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoragechecklocationbridgRequest;
use App\Models\Security\Modules\ModelHasRole;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\Storagechecklocationbridg;
use App\Models\Yard\StorageLocation;
use App\Traits\ResponseApi;

class StoragechecklocationbridgController extends Controller
{
    use ResponseApi;

    public function store(StoragechecklocationbridgRequest $request)
    {
        \Log::info($request->all());
        $orderDelivery = OrderDeliverie::controlAccessUser()->where('shipmentid', $request['shipmentid'])
            ->whereNotIn('statusid', ['CA', 'DH'])->first();
        if (empty($orderDelivery)) {
            return $this->getResponseWithErrors('savevalidacionshipmentbrid', 'El número de embarque no existe, favor de validar.');
        }
        $location = StorageLocation::controlAccessUser()->where('barcode', $request['locationid'])->first();
        if (empty($location)) {
            return $this->getResponseWithErrors('savevalidacionshipmentbrid', 'No existe la cortina ingresada, favor de validar.');
        }
        if ($location->zone->zoneid != 'CO'){
            return $this->getResponseWithErrors('savevalidacionshipmentbrid', 'La ubicación ingresada no pertenece a la zona CORTINA, favor de validar.');
        }
        $role = ModelHasRole::where('model_id', \Auth::user()->id)->first();
        Storagechecklocationbridg::create([
            'centerid' => $orderDelivery->centerid,
            'warehouseid' => $orderDelivery->warehouseid,
            'orderid' => $orderDelivery->id,
            'locationid' => $location->id,
            'turnid' => $request['turnid'],

            'complemento' => $request['complemento'],
            'totalizquierdo' => $request['totalizquierdo'],
            'totalderecho' => $request['totalderecho'],
            'totalfinal' => $request['totalfinal'],
            'detalleizquierdo' => $request['detalleizquierdo'],
            'detallederecho' => $request['detallederecho'],
            'name' => \Auth::user()->name,
            'roleid' => $role->role_id,
            'team' => $request['team']
        ]);
        return $this->getResponseWithSuccess('savevalidacionshipmentbrid', 'Registro guardado con exito');
    }


}
