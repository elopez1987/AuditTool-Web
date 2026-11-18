<?php

namespace App\Http\Controllers\Configuration\EdiShipment;

use App\Http\Controllers\BaseController;
use App\Models\Configuration\EdiShipment;

class EdiShipmentsController extends BaseController
{
    public function index()
    {
        $this->authorize('edishipments.index');
        $data = EdiShipment::controlAccessUser()
            ->with(['center:centerid,name,description', 'warehouse:warehouseid,name'])
            ->whereIn('status', ['Pending'])
            ->get();

        return view('Configuration.EdiShipments.index', compact('data'));
    }

    public function destroy($id)
    {
        $this->authorize('edishipments.delete');
        EdiShipment::destroy($id);

        return $this->redirectBackSuccess();
    }
}
