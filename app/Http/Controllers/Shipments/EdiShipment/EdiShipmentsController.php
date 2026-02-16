<?php

namespace App\Http\Controllers\Shipments\EdiShipment;

use App\Http\Controllers\BaseController;
use App\Models\Shipments\EdiShipment;

class EdiShipmentsController extends BaseController
{
    public function index()
    {
        $this->authorize('edishipments.index');
        $data = EdiShipment::controlAccessUser()->with(['center', 'warehouse'])
            ->leftJoin('orderdeliveries', 'edishipments.shipmentid', '=', 'orderdeliveries.shipmentid')
            ->select(['edishipments.*',
                'orderdeliveries.created_at as fecha',
            ])
            ->get();

        return view('Shipments.EdiShipments.index', compact('data'));
    }

    public function destroy($id)
    {
        $this->authorize('edishipments.delete');
        EdiShipment::destroy($id);

        return $this->redirectBackSuccess();
    }
}
