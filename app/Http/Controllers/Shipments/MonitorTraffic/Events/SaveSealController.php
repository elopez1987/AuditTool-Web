<?php

namespace App\Http\Controllers\Shipments\MonitorTraffic\Events;

use App\Http\Controllers\BaseController;
use App\Models\Shipments\ShipmentSeal;
use Illuminate\Http\Request;

class SaveSealController extends BaseController
{
    public function index(Request $request, $id)
    {
        $seal = ShipmentSeal::where('orderid', $id)->lockForUpdate()->first();
        if (empty($seal)) {
            return redirect()->back()->with('error', 'No se encontró el registro de embarque');
        }
        $seal->containerseal01 = $request['containerseal01'];
        $seal->containerseal02 = $request['containerseal02'];
        $seal->containerseal03 = $request['containerseal03'];
        $seal->receivername = $request['receivername'];
        $seal->save();

        return $this->redirectBackInfo();
    }
}
